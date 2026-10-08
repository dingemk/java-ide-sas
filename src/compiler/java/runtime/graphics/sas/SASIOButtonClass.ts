import { CallbackParameter } from "../../../../common/interpreter/CallbackParameter";
import { Thread } from "../../../../common/interpreter/Thread";
import { LibraryDeclarations } from "../../../module/libraries/DeclareType";
import { NonPrimitiveType } from "../../../types/NonPrimitiveType";
import { ObjectClass } from "../../system/javalang/ObjectClassStringClass";
import { ColorClass } from "../ColorClass";
import { attachSASIOControl, withSASIOWorld } from "./SASIOControls";

export class SASIOButtonClass extends ObjectClass {
    static __javaDeclarations: LibraryDeclarations = [
        { type: "declaration", package: "sasio", signature: "class Button extends Object" },
        { type: "method", signature: "Button(int x, int y, int width, int height, string text, Color color)", java: SASIOButtonClass.prototype._cj$_constructor_$Button$int$int$int$int$string$Color },
        { type: "method", signature: "boolean clicked()", native: SASIOButtonClass.prototype._clicked },
        { type: "method", signature: "void setHidden(boolean hidden)", native: SASIOButtonClass.prototype._setHidden },
        { type: "method", signature: "boolean getHidden()", native: SASIOButtonClass.prototype._getHidden },
        { type: "method", signature: "void setActivated(boolean active)", native: SASIOButtonClass.prototype._setActivated },
        { type: "method", signature: "boolean getActivated()", native: SASIOButtonClass.prototype._getActivated },
        { type: "method", signature: "string getButtonText()", native: SASIOButtonClass.prototype._getButtonText },
    ];
    static type: NonPrimitiveType;
    element!: HTMLButtonElement;
    private clickPending = false;

    _cj$_constructor_$Button$int$int$int$int$string$Color(t: Thread, callback: CallbackParameter,
        x: number, y: number, width: number, height: number, text: string, color: ColorClass) {
        withSASIOWorld(t, world => {
            this.element = document.createElement("button");
            this.element.type = "button";
            this.element.textContent = text;
            this.element.style.backgroundColor = color ? `rgb(${color.red},${color.green},${color.blue})` : "#eeeeee";
            this.element.addEventListener("click", () => {
                if (!this.element.disabled && !this.element.hidden) this.clickPending = true;
            });
            attachSASIOControl(world, this.element, x, y, width, height);
            t.s.push(this);
            callback?.();
        });
    }
    _clicked(): boolean {
        const result = this.clickPending;
        this.clickPending = false;
        return result;
    }
    _setHidden(hidden: boolean): void {
        this.element.hidden = hidden;
        if (hidden) { this.clickPending = false; this.element.blur(); }
    }
    _getHidden(): boolean { return this.element.hidden; }
    _setActivated(active: boolean): void {
        this.element.disabled = !active;
        this.element.style.color = active ? "#000000" : "#aaaaaa";
        if (!active) this.clickPending = false;
    }
    _getActivated(): boolean { return !this.element.disabled; }
    _getButtonText(): string { return this.element.textContent ?? ""; }
}
