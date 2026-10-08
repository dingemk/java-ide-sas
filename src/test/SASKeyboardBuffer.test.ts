import { describe, expect, it, vi } from 'vitest';
import { SASViewClass } from '../compiler/java/runtime/graphics/sas/SASViewClass';
import { EventManager } from '../compiler/common/interpreter/EventManager';
import { ThreadState } from '../compiler/common/interpreter/ThreadState';
import type { Thread } from '../compiler/common/interpreter/Thread';
import type { KeyDownListener } from '../compiler/common/interpreter/KeyboardManager';

function setup() {
    const listeners = new Set<KeyDownListener>();
    const events = new EventManager<'stop' | 'resetRuntime'>();
    const keyboard = {
        addKeyDownListener: (listener: KeyDownListener) => listeners.add(listener),
        removeKeyDownListener: (listener: KeyDownListener) => listeners.delete(listener),
        clearPressedKeys: vi.fn()
    };
    const view = new SASViewClass();
    Object.assign(view, { interpreter: { keyboardManager: keyboard, eventManager: events } });
    view['ensureSASKeyboardListener']();
    const thread = () => ({ s: [], state: ThreadState.running }) as unknown as Thread;
    return {
        view, keyboard, events, listeners, thread,
        key: (key: string, shift = false, ctrl = false, alt = false) => listeners.forEach(listener => listener(key, shift, ctrl, alt))
    };
}

describe('SAS keyGetChar', () => {
    it('consumes buffered characters in order, preserving case and spaces', () => {
        const f = setup();
        for (const key of ['A', 'b', '\u00e4', ' ']) f.key(key);
        const t = f.thread();
        for (let i = 0; i < 4; i++) f.view._mj$keyGetChar$char$(t, undefined);
        expect(t.s).toEqual(['A', 'b', '\u00e4', ' ']);
        f.view._mj$keyGetChar$char$(t, undefined);
        expect(t.state).toBe(ThreadState.waiting);
    });
    it('waits without blocking the browser, then resumes once on the next character', () => {
        const f = setup();
        const t = f.thread();
        const callback = vi.fn();
        f.view._mj$keyGetChar$char$(t, callback);
        expect(t.state).toBe(ThreadState.waiting);
        f.key('Shift', true);
        f.key('ArrowLeft');
        f.key('a', false, true);
        expect(t.s).toEqual([]);
        f.key('X', true);
        expect(t.s).toEqual(['X']);
        expect(t.state).toBe(ThreadState.running);
        expect(callback).toHaveBeenCalledOnce();
    });
    it('maps Enter, Backspace and Tab to Java control characters', () => {
        const f = setup();
        const t = f.thread();
        for (const key of ['Enter', 'Backspace', 'Tab']) f.key(key);
        for (let i = 0; i < 3; i++) f.view._mj$keyGetChar$char$(t, undefined);
        expect(t.s).toEqual(['\n', '\b', '\t']);
    });
    it('deletes buffered characters and the pressed-key state', () => {
        const f = setup();
        f.key('a');
        f.view._keyBufferDelete();
        expect(f.keyboard.clearPressedKeys).toHaveBeenCalledOnce();
        const t = f.thread();
        f.view._mj$keyGetChar$char$(t, undefined);
        expect(t.state).toBe(ThreadState.waiting);
        f.key('b');
        expect(t.s).toEqual(['b']);
    });
    it.each(['stop', 'resetRuntime'] as const)('unsubscribes on %s without resuming a waiting program', event => {
        const f = setup();
        const t = f.thread();
        const callback = vi.fn();
        f.view._mj$keyGetChar$char$(t, callback);
        f.events.fire(event);
        f.key('a');
        expect(f.listeners.size).toBe(0);
        expect(t.s).toEqual([]);
        expect(callback).not.toHaveBeenCalled();
    });
});
