import { describe, expect, it } from 'vitest';
import * as PIXI from 'pixi.js';
import { SASShapesClass } from '../compiler/java/runtime/graphics/sas/SASShapesClass';
import { ShapeClass } from '../compiler/java/runtime/graphics/ShapeClass';

function rectangle() {
    const shape = Object.create(SASShapesClass.prototype) as SASShapesClass;
    Object.assign(shape, {
        container: new PIXI.Container(),
        centerXInitial: 320,
        centerYInitial: 448,
        sasDirection: 0,
        angle: 0,
        directionRad: 0,
        setWorldTransformAndHitPolygonDirty() {},
        getWorldTransform() { return new PIXI.Matrix(); }
    });
    return shape;
}

describe('SAS clockwise rotation', () => {
    it('tips the obstacle to the right around its lower right corner', () => {
        const shape = rectangle();
        for (let i = 0; i < 45; i++) shape._turnAround(340, 538, 2);
        const matrix = shape.container.localTransform;
        const pivot = matrix.apply(new PIXI.Point(340, 538));
        const topRight = matrix.apply(new PIXI.Point(340, 358));
        expect(pivot.x).toBeCloseTo(340);
        expect(pivot.y).toBeCloseTo(538);
        expect(topRight.x).toBeCloseTo(520);
        expect(topRight.y).toBeCloseTo(538);
    });

    it('keeps turn and turnTo consistent with the SAS movement direction', () => {
        const shape = rectangle();
        shape._turn(90);
        expect(shape._getDirection()).toBe(90);
        const above = shape.container.localTransform.apply(new PIXI.Point(320, 438));
        expect(above.x).toBeCloseTo(330);
        expect(above.y).toBeCloseTo(448);
        shape._turnToAngle(0);
        const restored = shape.container.localTransform.apply(new PIXI.Point(320, 438));
        expect(restored.x).toBeCloseTo(320);
        expect(restored.y).toBeCloseTo(438);
    });

    it('preserves the counterclockwise convention of the original graphics API', () => {
        const shape = rectangle();
        ShapeClass.prototype._rotate.call(shape, 90, 340, 538);
        const topRight = shape.container.localTransform.apply(new PIXI.Point(340, 358));
        expect(topRight.x).toBeCloseTo(160);
        expect(topRight.y).toBeCloseTo(538);
    });
});
