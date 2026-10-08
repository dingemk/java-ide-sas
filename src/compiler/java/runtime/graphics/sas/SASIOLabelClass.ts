import { CallbackParameter } from "../../../../common/interpreter/CallbackParameter";
import { Thread } from "../../../../common/interpreter/Thread";
import { LibraryDeclarations } from "../../../module/libraries/DeclareType";
import { NonPrimitiveType } from "../../../types/NonPrimitiveType";
import { ColorClass } from "../ColorClass";
import { SASRectangleClass } from "./SASRectangleClass";
import { SASSpriteClass } from "./SASSpriteClass";
import { SASTextClass } from "./SASTextClass";

export class SASIOLabelClass extends SASSpriteClass {
    static __javaDeclarations: LibraryDeclarations = [
        { type: "declaration", package: "sasio", signature: "class Label extends sas.Sprite" },
        { type: "method", signature: "Label(int x, int y, int width, int height, string text, Color color)", java: SASIOLabelClass.prototype._cj$_constructor_$Label$int$int$int$int$string$Color },
        { type: "method", signature: "Label(int x, int y, int width, int height, string text, Color color, boolean right, boolean mono)", java: SASIOLabelClass.prototype._cj$_constructor_$Label$int$int$int$int$string$Color$boolean$boolean },
        { type: "method", signature: "void setLabelText(string text)", native: SASIOLabelClass.prototype._setLabelText },
        { type: "method", signature: "string getLabelText()", native: SASIOLabelClass.prototype._getLabelText },
    ];
    static type: NonPrimitiveType;
    private output!: SASTextClass;
    private labelX = 0;
    private labelY = 0;
    private labelWidth = 0;
    private labelHeight = 0;
    private right = false;
    private mono = false;

    _cj$_constructor_$Label$int$int$int$int$string$Color(t: Thread, callback: CallbackParameter,
        x: number, y: number, width: number, height: number, text: string, color: ColorClass) {
        this._cj$_constructor_$Label$int$int$int$int$string$Color$boolean$boolean(
            t, callback, x, y, width, height, text, color, false, false);
    }
    _cj$_constructor_$Label$int$int$int$int$string$Color$boolean$boolean(t: Thread, callback: CallbackParameter,
        x: number, y: number, width: number, height: number, text: string, color: ColorClass, right: boolean, mono: boolean) {
        this.labelX = x;
        this.labelY = y;
        this.labelWidth = width;
        this.labelHeight = height;
        this.right = right;
        this.mono = mono;
        this._cj$_constructor_$Sprite$(t, () => {
            const background = new SASRectangleClass();
            background._cj$_constructor_$Rectangle$double$double$double$double$Color(t, () => {
                this.add(t.s.pop());
                this.output = new SASTextClass();
                this.output._cj$_constructor_$Text$double$double$string(t, () => {
                    t.s.pop();
                    this._setLabelText(text);
                    this.add(this.output);
                    callback?.();
                }, x + 2, y, text);
            }, x, y, width, height, color);
        });
    }
    _getLabelText(): string { return this.output.text; }
    _setLabelText(text: string): void {
        this.output.text = text ?? "";
        this.output.textStyle.fontFamily = this.mono ? "monospace" : "Arial, sans-serif";
        this.output.fontsize = Math.max(1, Math.floor(this.labelHeight * 0.85));
        this.output.render();
        while (this.output.initialWidth > Math.max(0, this.labelWidth - 4) && this.output.fontsize > 1) {
            this.output.fontsize--;
            this.output.render();
        }
        // Update local placement so changing the caption preserves a moved/rotated label.
        this.output.container.position.set(this.right
            ? this.labelX + this.labelWidth - this.output.initialWidth : this.labelX + 2, this.labelY);
        this.output.container.updateLocalTransform();
        this.output.setWorldTransformAndHitPolygonDirty();
    }
}
