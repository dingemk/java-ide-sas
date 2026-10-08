import { afterEach, describe, expect, it, vi } from 'vitest';
import * as PIXI from 'pixi.js';
import { Thread } from '../compiler/common/interpreter/Thread';
import { ThreadState } from '../compiler/common/interpreter/ThreadState';
import { SASRectangleClass } from '../compiler/java/runtime/graphics/sas/SASRectangleClass';
import { SASCircleClass } from '../compiler/java/runtime/graphics/sas/SASCircleClass';
import { SASEllipseClass } from '../compiler/java/runtime/graphics/sas/SASEllipseClass';
import { SASPolygonClass } from '../compiler/java/runtime/graphics/sas/SASPolygonClass';
import { SASSpriteClass } from '../compiler/java/runtime/graphics/sas/SASSpriteClass';

function thread() {
    const world = { app: { stage: new PIXI.Container() }, shapesWhichBelongToNoGroup: [] };
    return { s: [], state: ThreadState.running, scheduler: { interpreter: {
        retrieveObject(name: string) {
            return name === 'WorldClass' ? world : 'https://example.org/java-ide/assets/';
        }
    } } } as unknown as Thread;
}

function rectangle(t: Thread) {
    const r = new SASRectangleClass();
    r._cj$_constructor_$Rectangle$double$double$double$double(t, undefined, 10, 20, 40, 60);
    t.s.pop();
    return r;
}

afterEach(() => vi.restoreAllMocks());

describe('SAS clones', () => {
    it.each([90, 75, 60, 48])('returns the Torwand ball exactly to its starting point after aiming at %s degrees', direction => {
        const t = thread();
        const ball = new SASCircleClass();
        ball._cj$_constructor_$Circle$double$double$double(t, undefined, 60, 500, 30);
        t.s.pop();
        for (let i = 0; i < 3; i++) {
            ball._turnToAngle(direction);
            ball._moveDistance(400);
            ball._moveToTopLeft(60, 500);
            expect(ball._getCenterX()).toBeCloseTo(90);
            expect(ball._getCenterY()).toBeCloseTo(530);
            expect(ball._getTopLeftX()).toBeCloseTo(60);
            expect(ball._getTopLeftY()).toBeCloseTo(500);
            expect(ball._getShapeWidth()).toBeCloseTo(60);
            expect(ball._getShapeHeight()).toBeCloseTo(60);
        }
    });
    it('uses the visible bounds of a nonuniformly scaled and rotated circle', () => {
        const t = thread();
        const ball = new SASCircleClass();
        ball._cj$_constructor_$Circle$double$double$double(t, undefined, 60, 500, 30);
        t.s.pop();
        ball._scaleXY(2, 1);
        ball._turn(45);
        expect(ball._getShapeWidth()).toBeCloseTo(2 * Math.hypot(60 / Math.sqrt(2), 30 / Math.sqrt(2)));
        expect(ball._getShapeHeight()).toBeCloseTo(ball._getShapeWidth());
        ball._moveToTopLeft(10, 20);
        expect(ball._getTopLeftX()).toBeCloseTo(10);
        expect(ball._getTopLeftY()).toBeCloseTo(20);
    });
    it('places the rotated cloned UFO wing by its visible top-left corner', () => {
        const t = thread();
        const left = new SASPolygonClass();
        left._cj$_constructor_$Polygon$double$double(t, undefined, 370, 430);
        t.s.pop();
        left._add(-30, 15);
        left._add(0, 30);
        left._mj$clone$Polygon$(t, undefined);
        const right = t.s.pop() as SASPolygonClass;
        right._turn(180);
        right._moveToTopLeft(450, 430);
        expect(right._getTopLeftX()).toBeCloseTo(450);
        expect(right._getTopLeftY()).toBeCloseTo(430);
        right.transformHitPolygon();
        expect(right.hitPolygonTransformed[0].x).toBeCloseTo(450);
        expect(right.hitPolygonTransformed[0].y).toBeCloseTo(460);
        expect(right.hitPolygonTransformed[1].x).toBeCloseTo(480);
        expect(right.hitPolygonTransformed[1].y).toBeCloseTo(445);
        expect(left._getTopLeftX()).toBeCloseTo(340);
        expect(left._getTopLeftY()).toBeCloseTo(430);
        const group = new SASSpriteClass();
        group._cj$_constructor_$Sprite$Shapes(t, undefined, right);
        t.s.pop();
        group._turn(35);
        group._moveToTopLeft(100, 200);
        expect(group._getTopLeftX()).toBeCloseTo(100);
        expect(group._getTopLeftY()).toBeCloseTo(200);
    });

    it('includes the constructor vertex in the Windrad mast polygon', () => {
        const t = thread();
        const mast = new SASPolygonClass();
        mast._cj$_constructor_$Polygon$double$double(t, undefined, 160, 550);
        t.s.pop();
        mast._add(20, 0);
        mast._add(18, -300);
        mast._add(2, -300);
        expect(mast.hitPolygonInitial).toEqual([
            { x: 160, y: 550 }, { x: 180, y: 550 },
            { x: 178, y: 250 }, { x: 162, y: 250 }
        ]);
        expect(mast._containsPoint(162, 540)).toBe(true);
        expect(mast.centerXInitial).toBe(170);
        expect(mast.centerYInitial).toBe(400);
        mast._mj$clone$Polygon$(t, undefined);
        const copy = t.s.pop() as SASPolygonClass;
        expect(copy.hitPolygonInitial).toEqual(mast.hitPolygonInitial);
        expect(copy.hitPolygonInitial).not.toBe(mast.hitPolygonInitial);
    });

    it('returns one fully initialized copy with its direction, texture and transform', () => {
        const t = thread();
        const r = rectangle(t);
        r._setDirection(180);
        r._move(70, 30);
        r.sasTexture = PIXI.Texture.WHITE;
        r._setHidden(true);
        let calls = 0;
        r._mj$clone$Shapes$(t, () => {
            calls++;
            const copy = t.s.at(-1) as SASRectangleClass;
            expect(copy.sasDirection).toBe(180);
            expect(copy.container.visible).toBe(false);
            expect(copy.sasTexture).toBe(r.sasTexture);
            expect(copy.container.localTransform).toEqual(r.container.localTransform);
        });
        expect(calls).toBe(1);
        expect(t.s).toHaveLength(1);
        expect(t.s[0]).not.toBe(r);
    });

    it('copies nested sprites without sharing children or changing their local transforms', () => {
        const t = thread();
        const inner = new SASSpriteClass();
        inner._cj$_constructor_$Sprite$(t, undefined);
        t.s.pop();
        const r = rectangle(t);
        inner.add(r);
        inner._move(40, 50);
        const outer = new SASSpriteClass();
        outer._cj$_constructor_$Sprite$Shapes(t, undefined, inner);
        t.s.pop();
        outer._turn(35);
        outer._move(30, 60);
        const callback = vi.fn();
        outer._mj$clone$Sprite$(t, callback);
        expect(callback).toHaveBeenCalledTimes(1);
        expect(t.s).toHaveLength(1);
        const copy = t.s[0] as SASSpriteClass;
        const child = copy.shapes[0] as SASSpriteClass;
        expect(copy.sasDirection).toBe(outer.sasDirection);
        expect(child).not.toBe(inner);
        expect(child.shapes[0]).not.toBe(r);
        expect(copy.container.localTransform).toEqual(outer.container.localTransform);
        expect(child.container.localTransform).toEqual(inner.container.localTransform);
        expect(child.shapes[0].getWorldTransform()).toEqual(r.getWorldTransform());
    });
});

describe('SAS textures', () => {
    it('recognizes filenames in constructors instead of painting them red', async () => {
        const t = thread();
        const r = new SASRectangleClass();
        const load = vi.spyOn(PIXI.Assets, 'load').mockResolvedValue(PIXI.Texture.WHITE as unknown as Record<string, unknown>);
        const callback = vi.fn();
        r._cj$_constructor_$Rectangle$double$double$double$double$string(t, callback, 10, 20, 40, 60, 'boote.jpg');
        expect(callback).not.toHaveBeenCalled();
        await vi.waitFor(() => expect(callback).toHaveBeenCalledTimes(1));
        expect(load).toHaveBeenCalledWith('https://example.org/java-ide/assets/boote.jpg');
        expect(r.sasTexture).toBe(PIXI.Texture.WHITE);
        expect(t.s).toEqual([r]);
    });

    it('registers every declared Sprite method with an implementation', () => {
        for (const declaration of SASSpriteClass.__javaDeclarations) {
            if (declaration.type === 'method') {
                expect(declaration.native ?? declaration.java ?? declaration.template, declaration.signature).toBeDefined();
            }
        }
    });

    it.each([
        ['Rectangle', SASRectangleClass], ['Circle', SASCircleClass],
        ['Ellipse', SASEllipseClass], ['Polygon', SASPolygonClass]
    ] as const)(
        'waits for a texture and fills the actual shape (%s)', async (_name, Shape) => {
            const t = thread();
            const r = new Shape();
            Object.assign(r, {
                world: t.scheduler.interpreter.retrieveObject('WorldClass'),
                left: 10, top: 20, shapeX: 10, shapeY: 20, radius: 15,
                initialWidth: 40, initialHeight: 60,
                hitPolygonInitial: [{ x: 0, y: 0 }, { x: 30, y: 0 }, { x: 0, y: 30 }]
            });
            r.render();
            const load = vi.spyOn(PIXI.Assets, 'load').mockResolvedValue(PIXI.Texture.WHITE as unknown as Record<string, unknown>);
            const callback = vi.fn();
            r._mj$loadTexture$void$string(t, callback, 'rinde.png');
            expect(t.state).toBe(ThreadState.waiting);
            expect(callback).not.toHaveBeenCalled();
            await vi.waitFor(() => expect(callback).toHaveBeenCalledTimes(1));
            expect(load).toHaveBeenCalledWith('https://example.org/java-ide/assets/rinde.png');
            expect(t.state).toBe(ThreadState.running);
            const instruction = (r.container as PIXI.Graphics).context.instructions.find(i => i.action === 'fill');
            expect((instruction?.data.style as PIXI.FillStyle)?.texture).toBe(PIXI.Texture.WHITE);
        }
    );

    it('continues once on load failure and keeps the current fill', async () => {
        const t = thread();
        const r = rectangle(t);
        const color = r.fillColor;
        vi.spyOn(PIXI.Assets, 'load').mockRejectedValue(new Error('missing'));
        const callback = vi.fn();
        r._mj$loadTexture$void$string(t, callback, 'missing.png');
        await vi.waitFor(() => expect(callback).toHaveBeenCalledTimes(1));
        expect(r.fillColor).toBe(color);
        expect(r.sasTexture).toBeUndefined();
    });
});
