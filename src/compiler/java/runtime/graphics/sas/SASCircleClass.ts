import * as PIXI from "pixi.js";
import { CallbackParameter } from "../../../../common/interpreter/CallbackParameter";
import { Thread } from "../../../../common/interpreter/Thread";
import { CallbackFunction } from "../../../../common/interpreter/StepFunction";
import { LibraryDeclarations } from "../../../module/libraries/DeclareType";
import { NonPrimitiveType } from "../../../types/NonPrimitiveType";
import { ColorClass } from "../ColorClass";
import { SASShapesClass } from "./SASShapesClass";

export class SASCircleClass extends SASShapesClass {
    static __javaDeclarations: LibraryDeclarations = [
        { type: "declaration", package: "sas", signature: "class Circle extends sas.Shapes" },

        { type: "method", signature: "Circle(double x, double y, double radius)", java: SASCircleClass.prototype._cj$_constructor_$Circle$double$double$double },
        { type: "method", signature: "Circle(double x, double y, double radius, Color color)", java: SASCircleClass.prototype._cj$_constructor_$Circle$double$double$double$Color },
        { type: "method", signature: "Circle(double x, double y, double radius, string color)", java: SASCircleClass.prototype._cj$_constructor_$Circle$double$double$double$string },
        { type: "method", signature: "Circle(double x, double y, double radius, int color)", java: SASCircleClass.prototype._cj$_constructor_$Circle$double$double$double$int },
        { type: "method", signature: "Circle clone()", java: SASCircleClass.prototype._mj$clone$Circle$ },
    ]

    static type: NonPrimitiveType;

    shapeX: number = 0;
    shapeY: number = 0;
    mx!: number;
    my!: number;
    radius!: number;

    get scaledRadius(): number {
        return this.scaleFactor * this.radius;
    }

    _getSASBounds() {
        const matrix = this.getWorldTransform();
        const center = matrix.apply(new PIXI.Point(this.mx, this.my));
        // A rotated circle's bounding square overestimates its visible bounds.
        const rx = Math.abs(this.radius) * Math.hypot(matrix.a, matrix.c);
        const ry = Math.abs(this.radius) * Math.hypot(matrix.b, matrix.d);
        return { minX: center.x - rx, minY: center.y - ry, width: 2 * rx, height: 2 * ry };
    }

    _cj$_constructor_$Circle$double$double$double(t: Thread, callback: CallbackFunction, x: number, y: number, radius: number) {
        this._cj$_constructor_$Circle$double$double$double$string(t, callback, x, y, radius, "black");
    }

    _cj$_constructor_$Circle$double$double$double$string(t: Thread, callback: CallbackFunction, x: number, y: number, radius: number, color: string) {
        this.shapeX = x;
        this.shapeY = y;
        this.initCircle(t, () => {
            this._setFillColorOrTextureString(t, callback, color);
        }, x + radius, y + radius, radius);
    }

    _cj$_constructor_$Circle$double$double$double$int(t: Thread, callback: CallbackFunction, x: number, y: number, radius: number, color: number) {
        this.shapeX = x;
        this.shapeY = y;
        this.initCircle(t, () => {
            this._setFillColorInt(color);
            if (callback) callback();
        }, x + radius, y + radius, radius);
    }

    _cj$_constructor_$Circle$double$double$double$Color(t: Thread, callback: CallbackFunction, x: number, y: number, radius: number, color: ColorClass) {
        this.shapeX = x;
        this.shapeY = y;
        this.initCircle(t, () => {
            this._setFillColorColor(color);
            if (callback) callback();
        }, x + radius, y + radius, radius);
    }

    private initCircle(t: Thread, callback: CallbackFunction, mx: number, my: number, radius: number) {
        this._cj$_constructor_$FilledShape$(t, () => {
            this.centerXInitial = mx;
            this.centerYInitial = my;

            this.mx = mx;
            this.my = my;
            this.radius = radius;
            this.initialWidth = radius * 2;
            this.initialHeight = radius * 2;

            this.render();
            if (callback) callback();
        });
    }

    render(): void {
        this.hitPolygonInitial = [];

        let deltaAlpha = Math.PI / 8;
        for (let i = 0; i < 16; i++) {
            let alpha = deltaAlpha * i;
            this.hitPolygonInitial.push({
                x: this.mx + this.radius * Math.cos(alpha),
                y: this.my + this.radius * Math.sin(alpha)
            });
        }
        this.hitPolygonDirty = true;

        let g: PIXI.Graphics = <any>this.container;

        if (g == null) {
            g = new PIXI.Graphics();
            this.container = g;
            this.world.app.stage.addChild(g);
        } else {
            g.clear();
        }

        g.circle(this.mx, this.my, this.radius);

        this.fillGraphics(g);

        if (this.borderColor != null) {
            g.stroke({
                width: this.borderWidth,
                color: this.borderColor,
                alpha: this.borderAlpha,
                alignment: 0.5
            });
        }

        g.closePath();
    }

    _mj$clone$Circle$(t: Thread, callback: CallbackParameter) {
        let copy = new SASCircleClass();
        copy._cj$_constructor_$Circle$double$double$double(t, () => {
            copy.copyFrom(this);
            copy.render();
            if (callback) callback();
        }, this.shapeX, this.shapeY, this.radius);
    }
}
