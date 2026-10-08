import * as PIXI from "pixi.js";
import { CallbackParameter } from "../../../../common/interpreter/CallbackParameter";
import { CallbackFunction } from "../../../../common/interpreter/StepFunction";
import { Thread } from "../../../../common/interpreter/Thread";
import { LibraryDeclarations } from "../../../module/libraries/DeclareType";
import { NonPrimitiveType } from "../../../types/NonPrimitiveType";
import { ColorClass } from "../ColorClass";
import { SASShapesClass } from "./SASShapesClass";
import { IllegalArgumentExceptionClass } from "../../system/javalang/IllegalArgumentException";

export class SASTextClass extends SASShapesClass {
    static __javaDeclarations: LibraryDeclarations = [
        { type: "declaration", package: "sas", signature: "class Text extends sas.Shapes" },

        { type: "method", signature: "Text(double x, double y, string text)", java: SASTextClass.prototype._cj$_constructor_$Text$double$double$string },
        { type: "method", signature: "Text(double x, double y, string text, Color color)", java: SASTextClass.prototype._cj$_constructor_$Text$double$double$string$Color },
        { type: "method", signature: "Text(double x, double y, string text, string color)", java: SASTextClass.prototype._cj$_constructor_$Text$double$double$string$string },
        { type: "method", signature: "Text(double x, double y, string text, int color)", java: SASTextClass.prototype._cj$_constructor_$Text$double$double$string$int },
        { type: "method", signature: "void setText(string text)", native: SASTextClass.prototype._setText },
        { type: "method", signature: "string getText()", template: "§1.text" },
        { type: "method", signature: "void setFontsize(double fontsize)", native: SASTextClass.prototype._setFontsize },
        { type: "method", signature: "void setFontSansSerif(boolean bold, int size)", native: SASTextClass.prototype._setFontSansSerif },
        { type: "method", signature: "void setFontSanSerif(boolean bold, int size)", native: SASTextClass.prototype._setFontSansSerif },
        { type: "method", signature: "void setFontSerif(boolean bold, int size)", native: SASTextClass.prototype._setFontSerif },
        { type: "method", signature: "void setFontMonospaced(boolean bold, int size)", native: SASTextClass.prototype._setFontMonospaced },
        { type: "method", signature: "double getFontsize()", template: "§1.fontsize" },
        { type: "method", signature: "Text clone()", java: SASTextClass.prototype._mj$clone$Text$ },
    ]

    static type: NonPrimitiveType;

    x!: number;
    y!: number;
    text!: string;
    fontsize: number = 20;
    textStyle: PIXI.TextStyle = new PIXI.TextStyle({
        fontFamily: "Arial",
        fontSize: 20,
        fill: 0x000000,
        wordWrap: false,
        align: "left",
    });

    _cj$_constructor_$Text$double$double$string(t: Thread, callback: CallbackFunction, x: number, y: number, text: string) {
        this._cj$_constructor_$Text$double$double$string$string(t, callback, x, y, text, "black");
    }

    _cj$_constructor_$Text$double$double$string$string(t: Thread, callback: CallbackFunction, x: number, y: number, text: string, color: string) {
        this.initText(t, () => {
            this._setFillColorString(color);
            if (callback) callback();
        }, x, y, text);
    }

    _cj$_constructor_$Text$double$double$string$int(t: Thread, callback: CallbackFunction, x: number, y: number, text: string, color: number) {
        this.initText(t, () => {
            this._setFillColorInt(color);
            if (callback) callback();
        }, x, y, text);
    }

    _cj$_constructor_$Text$double$double$string$Color(t: Thread, callback: CallbackFunction, x: number, y: number, text: string, color: ColorClass) {
        this.initText(t, () => {
            this._setFillColorColor(color);
            if (callback) callback();
        }, x, y, text);
    }

    private initText(t: Thread, callback: CallbackFunction, x: number, y: number, text: string) {
        this._cj$_constructor_$FilledShape$(t, () => {
            this.x = x;
            this.y = y;
            this.text = text;
            this.centerXInitial = 0;
            this.centerYInitial = 0;
            this.borderColor = undefined;
            this.render();
            if (callback) callback();
        });
    }

    render(): void {
        this.textStyle.fill = this.fillColor == null ? 0x000000 : this.fillColor;
        this.textStyle.fontSize = this.fontsize;

        let g: PIXI.Text = <any>this.container;
        if (!this.container) {
            g = new PIXI.Text({
                text: this.text,
                style: this.textStyle
            });
            this.container = g;
            this.container.localTransform.translate(this.x, this.y);
            this.container.setFromMatrix(this.container.localTransform);
            this.container.updateLocalTransform();
            //@ts-ignore
            this.container._didLocalTransformChangeId = this.container._didChangeId;
            this.world.app.stage.addChild(g);
        } else {
            g.text = this.text;
            g.alpha = this.fillAlpha;
            g.style = this.textStyle;
        }

        let tm = PIXI.CanvasTextMetrics.measureText(this.text ?? "", this.textStyle);
        let width = tm.width;
        let height = tm.height;
        this.initialWidth = width;
        this.initialHeight = height;
        this.centerXInitial = width / 2;
        this.centerYInitial = height / 2;
        this.hitPolygonInitial = [
            { x: 0, y: 0 },
            { x: 0, y: height },
            { x: width, y: height },
            { x: width, y: 0 }
        ];
        this.hitPolygonDirty = true;
    }

    _setText(text: string | number) {
        this.text = `${text}`;
        this.render();
    }

    _setFontsize(fontsize: number) {
        this.fontsize = fontsize || 10;
        this.render();
    }

    private setFont(family: string, bold: boolean, size: number): void {
        if (size < 0) throw new IllegalArgumentExceptionClass("Die Schriftgroesse darf nicht negativ sein.");
        this.textStyle.fontFamily = family;
        this.textStyle.fontWeight = bold ? "bold" : "normal";
        this.fontsize = size;
        this.render();
    }

    _setFontSansSerif(bold: boolean, size: number): void {
        this.setFont("Arial, sans-serif", bold, size);
    }

    _setFontSerif(bold: boolean, size: number): void {
        this.setFont("Times New Roman, serif", bold, size);
    }

    _setFontMonospaced(bold: boolean, size: number): void {
        this.setFont("monospace", bold, size);
    }

    _mj$clone$Text$(t: Thread, callback: CallbackParameter) {
        let copy = new SASTextClass();
        copy._cj$_constructor_$Text$double$double$string(t, () => {
            copy.copyFrom(this);
            copy.fontsize = this.fontsize;
            copy.textStyle = this.textStyle.clone();
            copy.render();
            if (callback) callback();
        }, this.x, this.y, this.text);
    }
}
