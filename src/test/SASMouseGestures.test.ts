import { describe, expect, it } from 'vitest';
import { SASShapesClass } from '../compiler/java/runtime/graphics/sas/SASShapesClass';
import { SASSpriteClass } from '../compiler/java/runtime/graphics/sas/SASSpriteClass';
import { SASPolygonClass } from '../compiler/java/runtime/graphics/sas/SASPolygonClass';
import type { InternalMouseListener, MouseEventKind } from '../compiler/java/runtime/graphics/MouseManager2D';

function fixture(prototype: object) {
    const listeners: InternalMouseListener[] = [];
    const shape = new (prototype.constructor as new () => SASShapesClass)();
    Object.assign(shape, {
        container: { visible: true },
        world: { mouseManager: { internalMouseListeners: listeners } },
        _containsPoint: (x: number, y: number) => x >= 0 && x <= 100 && y >= 0 && y <= 100,
        sasMouseX: 0, sasMouseY: 0,
        sasMousePressed: false, sasMouseDragged: false, sasMouseClicked: false,
        sasMouseDownStartedInside: false, sasMouseListenerRegistered: false
    });
    shape._mouseDragged();
    return {
        shape,
        event(kind: MouseEventKind, x: number, y: number) {
            listeners.forEach(listener => listener.onMouseEvent(kind, x, y));
        }
    };
}

describe.each([
    ['Polygon', SASPolygonClass.prototype],
    ['Sprite', SASSpriteClass.prototype]
])('SAS %s mouse gestures', (_name, prototype) => {
    it('consumes a stationary click exactly once', () => {
        const { shape, event } = fixture(prototype);
        event('mousedown', 20, 30);
        event('mousemove', 20, 30);
        expect(shape._mouseDragged()).toBe(false);
        event('mouseup', 20, 30);
        expect(shape._mouseClicked()).toBe(true);
        expect(shape._mouseClicked()).toBe(false);
    });

    it('does not rotate the Tangram piece by emitting a click after dragging', () => {
        const { shape, event } = fixture(prototype);
        event('mousedown', 20, 30);
        event('mousemove', 50, 60);
        expect(shape._mouseDragged()).toBe(true);
        expect(shape._getMouseX()).toBe(50);
        expect(shape._getMouseY()).toBe(60);
        event('mouseup', 50, 60);
        expect(shape._mouseDragged()).toBe(false);
        expect(shape._mouseClicked()).toBe(false);
        event('mousedown', 50, 60);
        event('mouseup', 50, 60);
        expect(shape._mouseClicked()).toBe(true);
    });

    it('keeps dragging outside the initial contour without starting other shapes', () => {
        const { shape, event } = fixture(prototype);
        event('mousedown', 20, 30);
        event('mousemove', 150, 160);
        expect(shape._mouseDragged()).toBe(true);
        event('mouseup', 150, 160);
        expect(shape._mouseDragged()).toBe(false);
        expect(shape._mouseClicked()).toBe(false);
        event('mousedown', 150, 160);
        event('mousemove', 20, 30);
        expect(shape._mouseDragged()).toBe(false);
        event('mouseup', 20, 30);
        expect(shape._mouseClicked()).toBe(false);
    });

    it('does not turn a drag back to its starting point into a click', () => {
        const { shape, event } = fixture(prototype);
        event('mousedown', 20, 30);
        event('mousemove', 40, 50);
        event('mousemove', 20, 30);
        event('mouseup', 20, 30);
        expect(shape._mouseClicked()).toBe(false);
    });
});
