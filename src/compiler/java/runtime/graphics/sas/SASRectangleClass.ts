import * as PIXI from "pixi.js";
import { CallbackParameter } from "../../../../common/interpreter/CallbackParameter";
import { Thread } from "../../../../common/interpreter/Thread";
import { CallbackFunction } from "../../../../common/interpreter/StepFunction";
import { LibraryDeclarations } from "../../../module/libraries/DeclareType";
import { NonPrimitiveType } from "../../../types/NonPrimitiveType";
import { ColorClass } from "../ColorClass";
import { SASShapesClass } from "./SASShapesClass";

export class SASRectangleClass extends SASShapesClass {
    static __javaDeclarations: LibraryDeclarations = [
        { type: "declaration", package: "sas", signature: "class Rectangle extends sas.Shapes" },

        { type: "method", signature: "Rectangle(double x, double y, double width, double height)", java: SASRectangleClass.prototype._cj$_constructor_$Rectangle$double$double$double$double },
        { type: "method", signature: "Rectangle(double x, double y, double width, double height, Color color)", java: SASRectangleClass.prototype._cj$_constructor_$Rectangle$double$double$double$double$Color },
        { type: "method", signature: "Rectangle(double x, double y, double width, double height, string color)", java: SASRectangleClass.prototype._cj$_constructor_$Rectangle$double$double$double$double$string },
        { type: "method", signature: "Rectangle(double x, double y, double width, double height, int color)", java: SASRectangleClass.prototype._cj$_constructor_$Rectangle$double$double$double$double$int },
        { type: "method", signature: "Rectangle clone()", java: SASRectangleClass.prototype._mj$clone$Rectangle$ },
    ]

    static type: NonPrimitiveType;
    left!: number;
    top!: number;

    get scaledWidth(): number {
        return this.scaleFactor * this.initialWidth;
    }

    get scaledHeight(): number {
        return this.scaleFactor * this.initialHeight;
    }

    _cj$_constructor_$Rectangle$double$double$double$double(t: Thread, callback: CallbackFunction, x: number, y: number, width: number, height: number) {
        this._cj$_constructor_$Rectangle$double$double$double$double$string(t, callback, x, y, width, height, "black");
    }

    _cj$_constructor_$Rectangle$double$double$double$double$string(t: Thread, callback: CallbackFunction, x: number, y: number, width: number, height: number, color: string) {
        this.initRectangle(t, () => {
            this._setFillColorOrTextureString(t, callback, color);
        }, x, y, width, height);
    }

    _cj$_constructor_$Rectangle$double$double$double$double$int(t: Thread, callback: CallbackFunction, x: number, y: number, width: number, height: number, color: number) {
        this.initRectangle(t, () => {
            this._setFillColorInt(color);
            if (callback) callback();
        }, x, y, width, height);
    }

    _cj$_constructor_$Rectangle$double$double$double$double$Color(t: Thread, callback: CallbackFunction, x: number, y: number, width: number, height: number, color: ColorClass) {
        this.initRectangle(t, () => {
            this._setFillColorColor(color);
            if (callback) callback();
        }, x, y, width, height);
    }

    private initRectangle(t: Thread, callback: CallbackFunction, left: number, top: number, width: number, height: number) {
        this._cj$_constructor_$FilledShape$(t, () => {
            this.centerXInitial = left + width / 2;
            this.centerYInitial = top + height / 2;

            this.left = left;
            this.top = top;
            this.initialWidth = width;
            this.initialHeight = height;
            this.calculateHitPolygonInitial();

            this.render();
            if (callback) callback();
        });
    }

    calculateHitPolygonInitial() {
        this.hitPolygonInitial = [
            { x: this.left, y: this.top },
            { x: this.left, y: this.top + this.initialHeight },
            { x: this.left + this.initialWidth, y: this.top + this.initialHeight },
            { x: this.left + this.initialWidth, y: this.top }
        ];
        this.hitPolygonDirty = true;
    }

    render(): void {
        let g: PIXI.Graphics = <any>this.container;

        if (!this.container) {
            g = new PIXI.Graphics();
            this.container = g;
            this.world.app.stage.addChild(g);
        } else {
            g.clear();
        }

        g.rect(this.left, this.top, this.initialWidth, this.initialHeight);

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

    _mj$clone$Rectangle$(t: Thread, callback: CallbackParameter) {
        let copy = new SASRectangleClass();
        copy._cj$_constructor_$Rectangle$double$double$double$double(t, () => {
            copy.copyFrom(this);
            copy.render();
            if (callback) callback();
        }, this.left, this.top, this.initialWidth, this.initialHeight);
    }
}
