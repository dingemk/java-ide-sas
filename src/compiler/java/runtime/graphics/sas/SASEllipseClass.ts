import * as PIXI from "pixi.js";
import { CallbackParameter } from "../../../../common/interpreter/CallbackParameter";
import { CallbackFunction } from "../../../../common/interpreter/StepFunction";
import { Thread } from "../../../../common/interpreter/Thread";
import { LibraryDeclarations } from "../../../module/libraries/DeclareType";
import { NonPrimitiveType } from "../../../types/NonPrimitiveType";
import { ColorClass } from "../ColorClass";
import { SASShapesClass } from "./SASShapesClass";

export class SASEllipseClass extends SASShapesClass {
    static __javaDeclarations: LibraryDeclarations = [
        { type: "declaration", package: "sas", signature: "class Ellipse extends sas.Shapes" },

        { type: "method", signature: "Ellipse(double x, double y, double width, double height)", java: SASEllipseClass.prototype._cj$_constructor_$Ellipse$double$double$double$double },
        { type: "method", signature: "Ellipse(double x, double y, double width, double height, Color color)", java: SASEllipseClass.prototype._cj$_constructor_$Ellipse$double$double$double$double$Color },
        { type: "method", signature: "Ellipse(double x, double y, double width, double height, string color)", java: SASEllipseClass.prototype._cj$_constructor_$Ellipse$double$double$double$double$string },
        { type: "method", signature: "Ellipse(double x, double y, double width, double height, int color)", java: SASEllipseClass.prototype._cj$_constructor_$Ellipse$double$double$double$double$int },
        { type: "method", signature: "Ellipse clone()", java: SASEllipseClass.prototype._mj$clone$Ellipse$ },
    ]

    static type: NonPrimitiveType;

    left!: number;
    top!: number;
    mx!: number;
    my!: number;
    radiusX!: number;
    radiusY!: number;

    _cj$_constructor_$Ellipse$double$double$double$double(t: Thread, callback: CallbackFunction, x: number, y: number, width: number, height: number) {
        this._cj$_constructor_$Ellipse$double$double$double$double$string(t, callback, x, y, width, height, "black");
    }

    _cj$_constructor_$Ellipse$double$double$double$double$string(t: Thread, callback: CallbackFunction, x: number, y: number, width: number, height: number, color: string) {
        this.initEllipse(t, () => {
            this._setFillColorOrTextureString(t, callback, color);
        }, x, y, width, height);
    }

    _cj$_constructor_$Ellipse$double$double$double$double$int(t: Thread, callback: CallbackFunction, x: number, y: number, width: number, height: number, color: number) {
        this.initEllipse(t, () => {
            this._setFillColorInt(color);
            if (callback) callback();
        }, x, y, width, height);
    }

    _cj$_constructor_$Ellipse$double$double$double$double$Color(t: Thread, callback: CallbackFunction, x: number, y: number, width: number, height: number, color: ColorClass) {
        this.initEllipse(t, () => {
            this._setFillColorColor(color);
            if (callback) callback();
        }, x, y, width, height);
    }

    private initEllipse(t: Thread, callback: CallbackFunction, left: number, top: number, width: number, height: number) {
        this._cj$_constructor_$FilledShape$(t, () => {
            this.left = left;
            this.top = top;
            this.initialWidth = width;
            this.initialHeight = height;
            this.mx = left + width / 2;
            this.my = top + height / 2;
            this.radiusX = width / 2;
            this.radiusY = height / 2;
            this.centerXInitial = this.mx;
            this.centerYInitial = this.my;
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
                x: this.mx + this.radiusX * Math.cos(alpha),
                y: this.my + this.radiusY * Math.sin(alpha)
            });
        }
        this.hitPolygonDirty = true;

        let g: PIXI.Graphics = <any>this.container;
        if (!this.container) {
            g = new PIXI.Graphics();
            this.container = g;
            this.world.app.stage.addChild(g);
        } else {
            g.clear();
        }

        g.ellipse(this.mx, this.my, this.radiusX, this.radiusY);

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

    _mj$clone$Ellipse$(t: Thread, callback: CallbackParameter) {
        let copy = new SASEllipseClass();
        copy._cj$_constructor_$Ellipse$double$double$double$double(t, () => {
            copy.copyFrom(this);
            copy.render();
            if (callback) callback();
        }, this.left, this.top, this.initialWidth, this.initialHeight);
    }
}
