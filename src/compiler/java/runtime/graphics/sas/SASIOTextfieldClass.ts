import { CallbackParameter } from "../../../../common/interpreter/CallbackParameter";
import { Thread } from "../../../../common/interpreter/Thread";
import { LibraryDeclarations } from "../../../module/libraries/DeclareType";
import { NonPrimitiveType } from "../../../types/NonPrimitiveType";
import { ObjectClass } from "../../system/javalang/ObjectClassStringClass";
import { SASViewClass } from "./SASViewClass";
import { attachSASIOControl } from "./SASIOControls";

export class SASIOTextfieldClass extends ObjectClass {
    static __javaDeclarations: LibraryDeclarations = [
        { type: "declaration", package: "sasio", signature: "class Textfield extends Object" },
        { type: "method", signature: "Textfield(int x, int y, int width, int height, string text, sas.View view)", java: SASIOTextfieldClass.prototype._cj$_constructor_$Textfield$int$int$int$int$string$View },
        { type: "method", signature: "string getText()", native: SASIOTextfieldClass.prototype._getText },
        { type: "method", signature: "void deleteText()", native: SASIOTextfieldClass.prototype._deleteText },
        { type: "method", signature: "void setActivated(boolean active)", native: SASIOTextfieldClass.prototype._setActivated },
        { type: "method", signature: "boolean getActivated()", native: SASIOTextfieldClass.prototype._getActivated },
        { type: "method", signature: "boolean clicked()", native: SASIOTextfieldClass.prototype._clicked },
        { type: "method", signature: "boolean enterPressed()", native: SASIOTextfieldClass.prototype._enterPressed },
        { type: "method", signature: "void setHidden(boolean hidden)", native: SASIOTextfieldClass.prototype._setHidden },
    ];
    static type: NonPrimitiveType;
    element!: HTMLInputElement;
    private clickPending = false;
    private enterPending = false;

    _cj$_constructor_$Textfield$int$int$int$int$string$View(t: Thread, callback: CallbackParameter,
        x: number, y: number, width: number, height: number, text: string, view: SASViewClass) {
        this.element = document.createElement("input");
        this.element.type = "text";
        this.element.value = text ?? "";
        this.element.setAttribute("aria-label", "SAS Textfield");
        this.element.style.backgroundColor = "#ffffff";
        this.element.addEventListener("mousedown", () => { this.clickPending = true; });
        this.element.addEventListener("keydown", event => {
            if (event.key === "Enter" && !event.repeat && !event.isComposing) {
                this.enterPending = true;
                event.preventDefault();
            }
        });
        attachSASIOControl(view, this.element, x, y, width, height);
        t.s.push(this);
        callback?.();
    }
    _getText(): string { return this.element.value; }
    _deleteText(): void { this.element.value = ""; }
    _setActivated(active: boolean): void {
        if (active && !this.element.hidden && !this.element.disabled) this.element.focus();
        else this.element.blur();
    }
    _getActivated(): boolean { return document.activeElement === this.element; }
    _clicked(): boolean {
        const result = this.clickPending;
        this.clickPending = false;
        return result;
    }
    _enterPressed(): boolean {
        const result = this.enterPending;
        this.enterPending = false;
        if (result) this._setActivated(false);
        return result;
    }
    _setHidden(hidden: boolean): void {
        this.element.hidden = hidden;
        if (hidden) {
            this.element.blur();
            this.clickPending = false;
            this.enterPending = false;
        }
    }
}
