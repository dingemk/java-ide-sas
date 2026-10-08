import { afterEach, describe, expect, it, vi } from 'vitest';
import { SASToolsClass } from '../compiler/java/runtime/graphics/sas/SASToolsClass';
import { SASTextClass } from '../compiler/java/runtime/graphics/sas/SASTextClass';
import { EventManager } from '../compiler/common/interpreter/EventManager';
import { ThreadState } from '../compiler/common/interpreter/ThreadState';
import type { Thread } from '../compiler/common/interpreter/Thread';

function setup() {
    const events = new EventManager<'stop' | 'resetRuntime'>();
    const t = { s: [], state: ThreadState.running, scheduler: { interpreter: { eventManager: events } } } as unknown as Thread;
    return { t, events };
}
afterEach(() => { vi.restoreAllMocks(); document.body.replaceChildren(); });

describe('SAS Tools direction and dialogs', () => {
    it.each(['', 'Hallo', 'Gr\u00fc\u00dfe <b>Text</b>'])('returns input unchanged: %s', value => {
        const { t } = setup();
        SASToolsClass._mj$inputDialog$string$string(t, 'Name?');
        const input = document.querySelector<HTMLInputElement>('dialog input')!;
        expect(document.activeElement).toBe(input);
        expect(t.state).toBe(ThreadState.waiting);
        input.value = value;
        const button = document.querySelector<HTMLButtonElement>('dialog button')!;
        button.click();
        button.click();
        expect(t.s).toEqual([value]);
        expect(t.state).toBe(ThreadState.running);
    });
    it('accepts Enter and restores focus', () => {
        const { t } = setup();
        const previous = document.createElement('button');
        document.body.append(previous);
        previous.focus();
        SASToolsClass._mj$inputDialog$string$string(t, 'Name?');
        const input = document.querySelector<HTMLInputElement>('dialog input')!;
        input.value = 'Klaus';
        input.dispatchEvent(new KeyboardEvent('keydown', { key: 'Enter', bubbles: true, cancelable: true }));
        expect(t.s).toEqual(['Klaus']);
        expect(document.activeElement).toBe(previous);
    });
    it.each(['button', 'cancel'])('returns null when input is cancelled using %s', action => {
        const { t } = setup();
        SASToolsClass._mj$inputDialog$string$string(t, 'Name?');
        if (action === 'cancel') document.querySelector('dialog')!.dispatchEvent(new Event('cancel', { cancelable: true }));
        else Array.from(document.querySelectorAll<HTMLButtonElement>('dialog button')).find(button => button.textContent === 'Abbrechen')!.click();
        expect(t.s).toEqual([null]);
        expect(t.state).toBe(ThreadState.running);
    });
    it.each(['stop', 'resetRuntime'] as const)('cleans up input on %s without resuming execution', event => {
        const { t, events } = setup();
        SASToolsClass._mj$inputDialog$string$string(t, 'Name?');
        const button = document.querySelector<HTMLButtonElement>('dialog button')!;
        events.fire(event);
        button.click();
        expect(document.querySelector('dialog')).toBeNull();
        expect(t.s).toEqual([]);
        expect(t.state).toBe(ThreadState.waiting);
    });
    it.each([[0,-10,0], [10,0,90], [0,10,180], [-10,0,270], [10,-10,45], [-10,10,225]])('gets direction to %s/%s', (dx, dy, angle) => {
        expect(SASToolsClass._getDirection(100, 200, 100 + dx, 200 + dy)).toBeCloseTo(angle);
    });
    it('matches the original undefined direction for coincident points', () => {
        expect(SASToolsClass._getDirection(10, 20, 10, 20)).toBeNaN();
    });
    it.each([['Ja', 0], ['Nein', 1], ['Schliessen', -1]] as const)('returns %s once and resumes the thread', (label, answer) => {
        const { t } = setup();
        SASToolsClass._mj$confirmDialog$int$string(t, 'Weiter?');
        expect(t.state).toBe(ThreadState.waiting);
        const button = Array.from(document.querySelectorAll('dialog button')).find(button => button.textContent === label)! as HTMLButtonElement;
        button.click();
        button.click();
        expect(t.s).toEqual([answer]);
        expect(t.state).toBe(ThreadState.running);
        expect(document.querySelector('dialog')).toBeNull();
    });
    it('returns -1 on Escape/cancel', () => {
        const { t } = setup();
        SASToolsClass._mj$confirmDialog$int$string(t, 'Weiter?');
        document.querySelector('dialog')!.dispatchEvent(new Event('cancel', { cancelable: true }));
        expect(t.s).toEqual([-1]);
    });
    it('renders message and title as plain text and waits for OK without a return value', () => {
        const { t } = setup();
        SASToolsClass._mj$message$void$string$string(t, '<img src=x>', 'Hinweis');
        expect(document.querySelector('dialog strong')!.textContent).toBe('Hinweis');
        expect(document.querySelector('dialog p')!.textContent).toBe('<img src=x>');
        expect(document.querySelector('dialog img')).toBeNull();
        expect(t.state).toBe(ThreadState.waiting);
        document.querySelector<HTMLButtonElement>('dialog button')!.click();
        expect(t.state).toBe(ThreadState.running);
        expect(t.s).toEqual([]);
    });
    it.each(['stop', 'resetRuntime'] as const)('removes the dialog on %s without resuming stopped code', event => {
        const { t, events } = setup();
        SASToolsClass._mj$confirmDialog$int$string(t, 'Weiter?');
        const button = document.querySelector<HTMLButtonElement>('dialog button')!;
        events.fire(event);
        button.click();
        expect(document.querySelector('dialog')).toBeNull();
        expect(t.state).toBe(ThreadState.waiting);
        expect(t.s).toEqual([]);
    });
});

describe('SAS Text fonts', () => {
    it('sets family, boldness and size, redraws, and switches bold back off', () => {
        const text = new SASTextClass();
        const render = vi.spyOn(text, 'render').mockImplementation(() => {});
        text._setFontSansSerif(true, 28);
        expect(text.textStyle.fontFamily).toBe('Arial, sans-serif');
        expect(text.textStyle.fontWeight).toBe('bold');
        expect(text.fontsize).toBe(28);
        text._setFontSerif(false, 16);
        expect(text.textStyle.fontFamily).toBe('Times New Roman, serif');
        expect(text.textStyle.fontWeight).toBe('normal');
        text._setFontMonospaced(true, 20);
        expect(text.textStyle.fontFamily).toBe('monospace');
        expect(render).toHaveBeenCalledTimes(3);
        expect(() => text._setFontSansSerif(true, -1)).toThrow();
    });
});
