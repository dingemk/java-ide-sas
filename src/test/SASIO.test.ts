import { afterEach, describe, expect, it, vi } from 'vitest';
import * as PIXI from 'pixi.js';
import '../compiler/common/interpreter/Interpreter';
import { Thread } from '../compiler/common/interpreter/Thread';
import { EventManager } from '../compiler/common/interpreter/EventManager';
import { SASViewClass } from '../compiler/java/runtime/graphics/sas/SASViewClass';
import { SASIOButtonClass } from '../compiler/java/runtime/graphics/sas/SASIOButtonClass';
import { SASIOTextfieldClass } from '../compiler/java/runtime/graphics/sas/SASIOTextfieldClass';
import { SASIOLabelClass } from '../compiler/java/runtime/graphics/sas/SASIOLabelClass';
import { SASIOStringFileToolsClass as Files } from '../compiler/java/runtime/graphics/sas/SASIOStringFileToolsClass';

function setup() {
    const events = new EventManager<'stop' | 'resetRuntime'>();
    const div = document.createElement('div');
    document.body.appendChild(div);
    const canvas = document.createElement('canvas');
    canvas.style.width = '300px';
    div.appendChild(canvas);
    const workspaceFiles = new Map([['vorhanden.txt', 'eins\r\nzwei\r\n']]);
    const manager = {
        read(name: string) { if (!workspaceFiles.has(name)) throw new Error('missing'); return workspaceFiles.get(name)!; },
        write(name: string, text: string) { workspaceFiles.set(name, text); },
        append(name: string, text: string) { workspaceFiles.set(name, this.read(name) + text); }
    };
    const world = Object.assign(new SASViewClass(), {
        width: 600, height: 400, graphicsDiv: div, app: { canvas, stage: new PIXI.Container() },
        shapesWhichBelongToNoGroup: [], interpreter: { eventManager: events }
    });
    const t = { s: [], scheduler: { interpreter: {
        fileManager: manager, retrieveObject: () => world
    } } } as unknown as Thread;
    return { t, world, events, workspaceFiles };
}

afterEach(() => { vi.restoreAllMocks(); document.body.replaceChildren(); });

describe('sasio controls', () => {
    it('latches Button clicks once, supports visibility/activation and cleans up on reset', () => {
        const { t, events } = setup();
        const button = new SASIOButtonClass();
        const callback = vi.fn();
        button._cj$_constructor_$Button$int$int$int$int$string$Color(t, callback, 40, 60, 100, 30, 'OK', null);
        expect(t.s).toEqual([button]);
        expect(callback).toHaveBeenCalledTimes(1);
        expect(button.element.style.left).toBe('20px');
        expect(button.element.style.transform).toBe('scale(0.5)');
        button.element.click();
        expect(button._clicked()).toBe(true);
        expect(button._clicked()).toBe(false);
        button._setActivated(false);
        button.element.click();
        expect(button._clicked()).toBe(false);
        expect(button._getActivated()).toBe(false);
        button._setHidden(true);
        expect(button._getHidden()).toBe(true);
        expect(button._getButtonText()).toBe('OK');
        events.fire('resetRuntime');
        expect(button.element.isConnected).toBe(false);
    });

    it('edits text natively, consumes click/Enter, blurs on Enter and isolates IDE shortcuts', () => {
        const { t, world, events } = setup();
        const field = new SASIOTextfieldClass();
        field._cj$_constructor_$Textfield$int$int$int$int$string$View(t, undefined, 10, 10, 180, 30, 'Hallo', world);
        field._setActivated(true);
        expect(field._getActivated()).toBe(true);
        field.element.value = 'Neuer Text';
        expect(field._getText()).toBe('Neuer Text');
        const parentKey = vi.fn();
        world.graphicsDiv!.addEventListener('keydown', parentKey);
        field.element.dispatchEvent(new MouseEvent('mousedown', { bubbles: true }));
        expect(field._clicked()).toBe(true);
        expect(field._clicked()).toBe(false);
        field.element.dispatchEvent(new KeyboardEvent('keydown', { key: 'Enter', bubbles: true }));
        expect(parentKey).not.toHaveBeenCalled();
        expect(field._enterPressed()).toBe(true);
        expect(field._enterPressed()).toBe(false);
        expect(field._getActivated()).toBe(false);
        field._deleteText();
        expect(field._getText()).toBe('');
        field._setHidden(true);
        expect(field.element.hidden).toBe(true);
        events.fire('stop');
        expect(field.element.disabled).toBe(true);
        events.fire('resetRuntime');
        expect(field.element.isConnected).toBe(false);
    });

    it('keeps Label a movable Sprite and fits right-aligned monospaced captions', () => {
        vi.spyOn(PIXI.CanvasTextMetrics, 'measureText').mockImplementation((text, style) => ({
            width: text.length * Number(style.fontSize) * 0.6, height: Number(style.fontSize)
        } as PIXI.CanvasTextMetrics));
        const { t } = setup();
        const label = new SASIOLabelClass();
        const callback = vi.fn();
        label._cj$_constructor_$Label$int$int$int$int$string$Color$boolean$boolean(
            t, callback, 20, 30, 100, 30, 'Ein langer Text', null, true, true);
        expect(t.s).toEqual([label]);
        expect(callback).toHaveBeenCalledTimes(1);
        expect(label.shapes).toHaveLength(2);
        expect(label.shapes[1].initialWidth).toBeLessThanOrEqual(96);
        label._move(50, 0);
        const transform = label.container.localTransform.clone();
        label._setLabelText('Neu');
        expect(label._getLabelText()).toBe('Neu');
        expect(label.container.localTransform).toEqual(transform);
    });
});

describe('sasio StringFileTools', () => {
    it('reads workspace lines, including empty lines and CRLF, and writes a final newline', () => {
        const { t, workspaceFiles } = setup();
        Files._mj$countFileRows$int$string(t, 'vorhanden.txt');
        expect(t.s.pop()).toBe(2);
        Files._mj$loadFileInString$string$string(t, 'vorhanden.txt');
        expect(t.s.pop()).toBe('eins\nzwei');
        Files._mj$writeInFile$void$string$string(t, 'vorhanden.txt', 'neu');
        expect(workspaceFiles.get('vorhanden.txt')).toBe('neu\n');
        Files._mj$appendToFile$void$string$string(t, 'vorhanden.txt', 'weiter');
        expect(workspaceFiles.get('vorhanden.txt')).toBe('neu\nweiter\n');
    });
    it('creates virtual files, isolates sessions and handles missing/empty files', () => {
        const { t } = setup();
        Files._mj$fileExists$boolean$string(t, 'neu.txt');
        expect(t.s.pop()).toBe(false);
        Files._mj$countFileRows$int$string(t, 'neu.txt');
        expect(t.s.pop()).toBe(-1);
        Files._mj$loadFileInStringArray$string_I$string(t, 'neu.txt');
        expect(t.s.pop()).toBeNull();
        Files._mj$writeInFile$void$string$string(t, 'neu.txt', 'A\n\nB');
        Files._mj$loadFileInStringArray$string_I$string(t, 'neu.txt');
        expect(t.s.pop()).toEqual(['A', '', 'B']);
        const other = setup().t;
        Files._mj$fileExists$boolean$string(other, 'neu.txt');
        expect(other.s.pop()).toBe(false);
    });
});
