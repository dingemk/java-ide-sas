import { afterEach, describe, expect, it, vi } from 'vitest';
import * as PIXI from 'pixi.js';
import { alphaToArea } from '../compiler/java/runtime/graphics/sas/SASCollision';
import { SASPictureClass } from '../compiler/java/runtime/graphics/sas/SASPictureClass';
import { SASPolygonClass } from '../compiler/java/runtime/graphics/sas/SASPolygonClass';
import { SASRectangleClass } from '../compiler/java/runtime/graphics/sas/SASRectangleClass';
import { SASSpriteClass } from '../compiler/java/runtime/graphics/sas/SASSpriteClass';
import { ThreadState } from '../compiler/common/interpreter/ThreadState';
import type { Thread } from '../compiler/common/interpreter/Thread';

function fixture() {
    const world = { app: { stage: new PIXI.Container() }, shapesWhichBelongToNoGroup: [] };
    const t = { s: [], state: ThreadState.running, scheduler: { interpreter: { retrieveObject: (name: string) => name === 'WorldClass' ? world : undefined } } } as unknown as Thread;
    return { world, t };
}
function pixels(rows: string[]) {
    const data = new Uint8ClampedArray(rows.length * rows[0].length * 4);
    rows.forEach((row, y) => [...row].forEach((pixel, x) => { data[(y * row.length + x) * 4 + 3] = pixel === '.' ? 0 : pixel === '+' ? 1 : 255; }));
    return data;
}
function picture(rows: string[], x = 0, y = 0) {
    const { world } = fixture();
    const p = new SASPictureClass();
    Object.assign(p, { world, left: x, top: y, initialWidth: rows[0].length, initialHeight: rows.length, centerXInitial: x + rows[0].length / 2, centerYInitial: y + rows.length / 2 });
    p.sasCollisionArea = alphaToArea(pixels(rows), rows[0].length, rows.length).map(poly => poly.map(ring => ring.map(([px, py]) => [px + x, py + y])));
    p.render();
    return p;
}
function rectangle(t: Thread, x: number, y: number, w = 0.5, h = 0.5) {
    const r = new SASRectangleClass();
    r._cj$_constructor_$Rectangle$double$double$double$double(t, undefined, x, y, w, h);
    t.s.pop();
    return r;
}
afterEach(() => vi.restoreAllMocks());

describe('SAS image alpha collisions', () => {
    it('ignores transparent background in either argument order and includes alpha=1', () => {
        const { t } = fixture();
        const image = picture(['....', '.#+.', '.##.', '....']);
        const transparent = rectangle(t, 0.1, 0.1);
        const opaque = rectangle(t, 1.1, 1.1);
        expect(image._collidesWith(transparent)).toBe(false);
        expect(transparent._collidesWith(image)).toBe(false);
        expect(image._collidesWith(opaque)).toBe(true);
        expect(opaque._collidesWith(image)).toBe(true);
        expect(image._containsPoint(0.5, 0.5)).toBe(false);
        expect(image._containsPoint(2.5, 1.5)).toBe(true);
        expect(image._containsShape(opaque)).toBe(true);
        expect(image._containsShape(transparent)).toBe(false);
    });
    it('preserves holes and does not count mere edge contact as intersection', () => {
        const { t } = fixture();
        const image = picture(['#####', '#...#', '#...#', '#...#', '#####']);
        const hole = rectangle(t, 2, 2);
        const edge = rectangle(t, 5, 1);
        expect(image._containsPoint(2.5, 2.5)).toBe(false);
        expect(image._collidesWith(hole)).toBe(false);
        expect(image._containsShape(hole)).toBe(false);
        expect(image._collidesWith(edge)).toBe(false);
        expect(image._containsShape(rectangle(t, 0.1, 0.1, 4.8, 4.8))).toBe(false);
    });
    it('compares two masks rather than their overlapping image rectangles', () => {
        const a = picture(['#...', '....']);
        const b = picture(['...#', '....']);
        expect(a._collidesWith(b)).toBe(false);
        b._move(-3, 0);
        expect(a._collidesWith(b)).toBe(true);
        expect(a._collidesWith(picture(['....', '....']))).toBe(false);
    });
    it('keeps masks correct after rotation, scaling, mirroring, grouping and hiding', () => {
        const { t } = fixture();
        const image = picture(['#...', '....']);
        image._turnAround(0, 0, 90);
        image._scaleXY(2, 2);
        image._flipHorizontal();
        const center = image.getWorldTransform().apply(new PIXI.Point(0.5, 0.5));
        const empty = image.getWorldTransform().apply(new PIXI.Point(3.5, 0.5));
        expect(image._containsPoint(center.x, center.y)).toBe(true);
        expect(image._containsPoint(empty.x, empty.y)).toBe(false);
        const group = new SASSpriteClass();
        group._cj$_constructor_$Sprite$Shapes(t, undefined, image);
        t.s.pop();
        group._move(20, 30);
        expect(group._containsPoint(center.x + 20, center.y + 30)).toBe(true);
        expect(group._containsPoint(empty.x + 20, empty.y + 30)).toBe(false);
        const target = rectangle(t, center.x + 20 - 0.1, center.y + 30 - 0.1, 0.2, 0.2);
        expect(group._collidesWith(target)).toBe(true);
        group._setHidden(true);
        expect(target._collidesWith(group)).toBe(false);
    });
    it('loads intrinsic dimensions, derives alpha and preserves it through cloning', async () => {
        const { t } = fixture();
        const texture = new PIXI.Texture({ source: new PIXI.TextureSource({ width: 4, height: 2 }) });
        vi.spyOn(PIXI.Assets, 'load').mockResolvedValue(texture as unknown as Record<string, unknown>);
        const read = vi.fn(() => ({ data: pixels(['#...', '....']) }));
        vi.spyOn(HTMLCanvasElement.prototype, 'getContext').mockReturnValue({ drawImage() {}, getImageData: read } as unknown as ReturnType<HTMLCanvasElement['getContext']>);
        const p = new SASPictureClass();
        const callback = vi.fn();
        p._cj$_constructor_$Picture$double$double$string(t, callback, 10, 20, 'a.png');
        await vi.waitFor(() => expect(callback).toHaveBeenCalledOnce());
        t.s.pop();
        expect(p.initialWidth).toBe(4);
        expect(p.initialHeight).toBe(2);
        p._move(20, 30);
        const cloned = vi.fn();
        p._mj$clone$Picture$(t, cloned);
        await vi.waitFor(() => expect(cloned).toHaveBeenCalledOnce());
        const copy = t.s.pop() as SASPictureClass;
        expect(copy._containsPoint(30.5, 50.5)).toBe(true);
        expect(copy._containsPoint(33.5, 50.5)).toBe(false);
        expect(read).toHaveBeenCalledOnce();
    });
});

describe('SAS concave polygon collision and containment', () => {
    it('tests the L-shaped contour and rejects a contained-vertices bridge across its notch', () => {
        const { t } = fixture();
        const l = new SASPolygonClass();
        l._cj$_constructor_$Polygon$double$double(t, undefined, 0, 0);
        t.s.pop();
        for (const [x, y] of [[6,0], [6,2], [2,2], [2,6], [0,6]]) l._add(x, y);
        const notch = rectangle(t, 3, 3, 1, 1);
        expect(l._collidesWith(notch)).toBe(false);
        expect(l._collidesWith(rectangle(t, 0.5, 3, 1, 1))).toBe(true);
        const bridge = new SASPolygonClass();
        bridge._cj$_constructor_$Polygon$double$double(t, undefined, 1, 1);
        t.s.pop();
        bridge._add(4, 0); bridge._add(0, 4);
        expect(l._containsShape(bridge)).toBe(false);
        l._turn(45); l._move(10, 20);
        const inside = l.getWorldTransform().apply(new PIXI.Point(1, 4));
        const outside = l.getWorldTransform().apply(new PIXI.Point(4, 4));
        expect(l._containsPoint(inside.x, inside.y)).toBe(true);
        expect(l._containsPoint(outside.x, outside.y)).toBe(false);
    });
});
