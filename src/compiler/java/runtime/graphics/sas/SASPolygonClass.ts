import * as PIXI from "pixi.js";
import { polygonEnthältPunkt } from "../../../../../tools/MatheTools";
import { CallbackParameter } from "../../../../common/interpreter/CallbackParameter";
import { CallbackFunction } from "../../../../common/interpreter/StepFunction";
import { Thread } from "../../../../common/interpreter/Thread";
import { LibraryDeclarations } from "../../../module/libraries/DeclareType";
import { NonPrimitiveType } from "../../../types/NonPrimitiveType";
import { ColorClass } from "../ColorClass";
import { SASShapesClass } from "./SASShapesClass";

export class SASPolygonClass extends SASShapesClass {
    static __javaDeclarations: LibraryDeclarations = [
        { type: "declaration", package: "sas", signature: "class Polygon extends sas.Shapes" },

        { type: "method", signature: "Polygon(double x, double y)", java: SASPolygonClass.prototype._cj$_constructor_$Polygon$double$double },
        { type: "method", signature: "Polygon(double x, double y, Color color)", java: SASPolygonClass.prototype._cj$_constructor_$Polygon$double$double$Color },
        { type: "method", signature: "Polygon(double x, double y, string color)", java: SASPolygonClass.prototype._cj$_constructor_$Polygon$double$double$string },
        { type: "method", signature: "Polygon(double x, double y, int color)", java: SASPolygonClass.prototype._cj$_constructor_$Polygon$double$double$int },
        { type: "method", signature: "void add(double dx, double dy)", native: SASPolygonClass.prototype._add },
        { type: "method", signature: "Polygon clone()", java: SASPolygonClass.prototype._mj$clone$Polygon$ },
    ]

    static type: NonPrimitiveType;

    originX!: number;
    originY!: number;

    _cj$_constructor_$Polygon$double$double(t: Thread, callback: CallbackFunction, x: number, y: number) {
        this._cj$_constructor_$Polygon$double$double$string(t, callback, x, y, "black");
    }

    _cj$_constructor_$Polygon$double$double$string(t: Thread, callback: CallbackFunction, x: number, y: number, color: string) {
        this.initPolygon(t, () => {
            this._setFillColorOrTextureString(t, callback, color);
        }, x, y);
    }

    _cj$_constructor_$Polygon$double$double$int(t: Thread, callback: CallbackFunction, x: number, y: number, color: number) {
        this.initPolygon(t, () => {
            this._setFillColorInt(color);
            if (callback) callback();
        }, x, y);
    }

    _cj$_constructor_$Polygon$double$double$Color(t: Thread, callback: CallbackFunction, x: number, y: number, color: ColorClass) {
        this.initPolygon(t, () => {
            this._setFillColorColor(color);
            if (callback) callback();
        }, x, y);
    }

    private initPolygon(t: Thread, callback: CallbackFunction, x: number, y: number) {
        this._cj$_constructor_$FilledShape$(t, () => {
            this.originX = x;
            this.originY = y;
            this.centerXInitial = x;
            this.centerYInitial = y;
            this.hitPolygonInitial = [{ x, y }];
            this.render();
            if (callback) callback();
        });
    }

    _add(dx: number, dy: number) {
        this.hitPolygonInitial.push({ x: this.originX + dx, y: this.originY + dy });
        this.recalculateCenter();
        this.hitPolygonDirty = true;
        this.render();
    }

    private recalculateCenter() {
        if (this.hitPolygonInitial.length == 0) {
            this.centerXInitial = this.originX;
            this.centerYInitial = this.originY;
            return;
        }

        const xs = this.hitPolygonInitial.map(point => point.x);
        const ys = this.hitPolygonInitial.map(point => point.y);
        this.centerXInitial = (Math.min(...xs) + Math.max(...xs)) / 2;
        this.centerYInitial = (Math.min(...ys) + Math.max(...ys)) / 2;
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

        if (this.hitPolygonInitial.length > 0) {
            g.moveTo(this.hitPolygonInitial[0].x, this.hitPolygonInitial[0].y);
            for (let i = 1; i < this.hitPolygonInitial.length; i++) {
                g.lineTo(this.hitPolygonInitial[i].x, this.hitPolygonInitial[i].y);
            }
            g.closePath();
        }

        this.fillGraphics(g);

        if (this.borderColor != null) {
            g.stroke({
                width: this.borderWidth,
                color: this.borderColor,
                alpha: this.borderAlpha,
                alignment: 0.5
            });
        }
    }

    _containsPoint(x: number, y: number) {
        if (this.hitPolygonInitial.length < 3) return false;
        if (!this.container.getBounds().containsPoint(x, y)) return false;
        if (this.hitPolygonDirty) this.transformHitPolygon();
        return polygonEnthältPunkt(this.hitPolygonTransformed, { x, y });
    }

    _mj$clone$Polygon$(t: Thread, callback: CallbackParameter) {
        let copy = new SASPolygonClass();
        copy._cj$_constructor_$Polygon$double$double(t, () => {
            copy.hitPolygonInitial = this.hitPolygonInitial.slice();
            copy.copyFrom(this);
            copy.recalculateCenter();
            copy.render();
            if (callback) callback();
        }, this.originX, this.originY);
    }
}
