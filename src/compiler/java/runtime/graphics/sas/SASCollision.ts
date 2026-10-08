import polygonClipping from "polygon-clipping";
import type { MultiPolygon } from "polygon-clipping";
import { polygonEnthältPunkt } from "../../../../../tools/MatheTools";
import type { ShapeClass } from "../ShapeClass";

const { difference, intersection, union } = polygonClipping;

export function alphaToArea(data: ArrayLike<number>, width: number, height: number): MultiPolygon {
    if (data.length < width * height * 4) throw new Error("Incomplete image pixels");
    const rectangles: MultiPolygon = [];
    let previous = new Map<string, MultiPolygon[number]>();
    for (let y = 0; y < height; y++) {
        const current = new Map<string, MultiPolygon[number]>();
        let x = 0;
        while (x < width) {
            while (x < width && data[(y * width + x) * 4 + 3] === 0) x++;
            const start = x;
            while (x < width && data[(y * width + x) * 4 + 3] > 0) x++;
            if (start === x) continue;
            const key = `${start}:${x}`;
            const rectangle = previous.get(key) ?? [[[start, y], [x, y], [x, y + 1], [start, y + 1], [start, y]]];
            if (previous.has(key)) {
                rectangle[0][2][1] = y + 1;
                rectangle[0][3][1] = y + 1;
            } else {
                rectangles.push(rectangle);
            }
            current.set(key, rectangle);
        }
        previous = current;
    }
    return rectangles.length ? union(rectangles) : [];
}

type CollisionShape = ShapeClass & { sasCollisionArea?: MultiPolygon; shapes?: ShapeClass[] };

function visible(shape: ShapeClass): boolean {
    if (!shape?.container || shape.isDestroyed) return false;
    for (let container = shape.container; container; container = container.parent) {
        if (!container.visible) return false;
    }
    return true;
}

export function getSASArea(shape: ShapeClass): MultiPolygon {
    if (!visible(shape)) return [];
    const collisionShape = shape as CollisionShape;
    if (collisionShape.shapes) {
        const children = collisionShape.shapes.map(getSASArea).filter(area => area.length);
        return children.length ? union(children[0], ...children.slice(1)) : [];
    }
    const polygon = shape.hitPolygonInitial;
    if (collisionShape.sasCollisionArea === undefined && (!polygon || polygon.length < 3)) return [];
    const local: MultiPolygon = collisionShape.sasCollisionArea ?? [[[
        ...polygon.map(point => [point.x, point.y] as [number, number]),
        [polygon[0].x, polygon[0].y]
    ]]];
    const matrix = shape.getWorldTransform();
    if (Math.abs(matrix.a * matrix.d - matrix.b * matrix.c) < 1e-14) return [];
    return local.map(polygon => polygon.map(ring => ring.map(([x, y]) => [
        matrix.a * x + matrix.c * y + matrix.tx,
        matrix.b * x + matrix.d * y + matrix.ty
    ])));
}

export function intersectsSAS(a: ShapeClass, b: ShapeClass): boolean {
    if (!visible(a) || !visible(b) || !a.hasOverlappingBoundingBoxWith(b)) return false;
    const areaA = getSASArea(a);
    const areaB = getSASArea(b);
    return areaA.length > 0 && areaB.length > 0 && intersection(areaA, areaB).length > 0;
}

export function containsSAS(a: ShapeClass, b: ShapeClass): boolean {
    if (!visible(a) || !visible(b)) return false;
    const areaA = getSASArea(a);
    const areaB = getSASArea(b);
    if (!areaB.length) return true;
    return areaA.length > 0 && difference(areaB, areaA).length === 0;
}

export function containsSASPoint(shape: ShapeClass, x: number, y: number): boolean {
    return getSASArea(shape).some(polygon => {
        const contains = (ring: [number, number][]) => polygonEnthältPunkt(ring.map(([x, y]) => ({ x, y })), { x, y });
        return contains(polygon[0]) && !polygon.slice(1).some(contains);
    });
}
