import { CallbackParameter } from "../../../../common/interpreter/CallbackParameter";
import { Thread } from "../../../../common/interpreter/Thread";
import { ThreadState } from "../../../../common/interpreter/ThreadState";
import { LibraryDeclarations } from "../../../module/libraries/DeclareType";
import { NonPrimitiveType } from "../../../types/NonPrimitiveType";
import { ColorClass } from "../ColorClass";
import { InternalMouseListener, MouseEventKind } from "../MouseManager2D";
import { ShapeClass } from "../ShapeClass";
import { WorldClass } from "../WorldClass";

export class SASViewClass extends WorldClass {
    static __javaDeclarations: LibraryDeclarations = [
        { type: "declaration", package: "sas", signature: "class View extends World" },

        { type: "method", signature: "View()", java: SASViewClass.prototype._cj$_constructor_$View$ },
        { type: "method", signature: "View(string name)", java: SASViewClass.prototype._cj$_constructor_$View$string },
        { type: "method", signature: "View(int width, int height)", java: SASViewClass.prototype._cj$_constructor_$View$int$int },
        { type: "method", signature: "View(int width, int height, string name)", java: SASViewClass.prototype._cj$_constructor_$View$int$int$string },
        { type: "method", signature: "static View getView()", native: SASViewClass._getView },
        { type: "method", signature: "int getWidth()", template: `Math.round(§1.width)` },
        { type: "method", signature: "int getHeight()", template: `Math.round(§1.height)` },
        { type: "method", signature: "void remove(sas.Shapes shape)", native: SASViewClass.prototype._remove },
        { type: "method", signature: "void setSize(int width, int height)", native: SASViewClass.prototype._setSize },
        { type: "method", signature: "void setName(string name)", native: SASViewClass.prototype._setName },
        { type: "method", signature: "void setBackgroundColor(Color color)", native: SASViewClass.prototype._setBackgroundColorColor },
        { type: "method", signature: "void setBackgroundColor(int color)", native: SASViewClass.prototype._setBackgroundColor },
        { type: "method", signature: "void setBackgroundColor(string color)", native: SASViewClass.prototype._setBackgroundColor },
        { type: "method", signature: "void wait(int milliseconds)", java: SASViewClass.prototype._mj$wait$void$int },
        { type: "method", signature: "void keyBufferDelete()", native: SASViewClass.prototype._keyBufferDelete },
        { type: "method", signature: "char keyGetChar()", java: SASViewClass.prototype._mj$keyGetChar$char$ },
        { type: "method", signature: "boolean keyPressed()", native: SASViewClass.prototype._keyPressed },
        { type: "method", signature: "boolean keyPressed(char character)", native: SASViewClass.prototype._keyPressedChar },
        { type: "method", signature: "boolean keyLeftPressed()", native: SASViewClass.prototype._keyLeftPressed },
        { type: "method", signature: "boolean keyRightPressed()", native: SASViewClass.prototype._keyRightPressed },
        { type: "method", signature: "boolean keyUpPressed()", native: SASViewClass.prototype._keyUpPressed },
        { type: "method", signature: "boolean keyDownPressed()", native: SASViewClass.prototype._keyDownPressed },
        { type: "method", signature: "boolean keyEnterPressed()", native: SASViewClass.prototype._keyEnterPressed },
        { type: "method", signature: "boolean keyBackspacePressed()", native: SASViewClass.prototype._keyBackspacePressed },
        { type: "method", signature: "int getMouseX()", native: SASViewClass.prototype._getMouseX },
        { type: "method", signature: "int getMouseY()", native: SASViewClass.prototype._getMouseY },
    ]

    static type: NonPrimitiveType;

    name: string = "SaS-Programm";
    private sasMouseX: number = 0;
    private sasMouseY: number = 0;
    private sasMouseListenerRegistered: boolean = false;
    private sasKeyBuffer: string[] = [];
    private sasKeyWaiters: { thread: Thread; callback: CallbackParameter }[] = [];
    private sasKeyboardListenerRegistered = false;

    _cj$_constructor_$View$(t: Thread, callback: CallbackParameter) {
        this._cj$_constructor_$View$int$int$string(t, callback, 600, 400, "SaS-Programm");
    }

    _cj$_constructor_$View$string(t: Thread, callback: CallbackParameter, name: string) {
        this._cj$_constructor_$View$int$int$string(t, callback, 600, 400, name);
    }

    _cj$_constructor_$View$int$int(t: Thread, callback: CallbackParameter, width: number, height: number) {
        this._cj$_constructor_$View$int$int$string(t, callback, width, height, "SaS-Programm");
    }

    _cj$_constructor_$View$int$int$string(t: Thread, callback: CallbackParameter, width: number, height: number, name: string) {
        this.name = name;
        this._cj$_constructor_$World$int$int(t, () => {
            this.ensureSASMouseListener();
            this.ensureSASKeyboardListener();
            if (callback) callback();
        }, width, height);
    }

    static _getView(t: Thread) {
        t.s.push(t.scheduler.interpreter.retrieveObject("WorldClass") ?? null);
    }

    _setSize(width: number, height: number): void {
        this.changeResolution(this.interpreter, width, height);
    }

    _setName(name: string): void {
        this.name = name;
    }

    _remove(shape: ShapeClass): void {
        shape?.destroy();
    }

    _setBackgroundColorColor(color: ColorClass): void {
        super._setBackgroundColorColor(color);
    }

    _mj$wait$void$int(t: Thread, callback: CallbackParameter, milliseconds: number): void {
        if (milliseconds <= 0) {
            if (callback) callback();
            return;
        }

        t.state = ThreadState.timedWaiting;
        setTimeout(() => {
            if (t.state == ThreadState.timedWaiting) {
                t.state = ThreadState.running;
            }
            if (callback) callback();
        }, milliseconds);
    }

    _keyBufferDelete(): void {
        this.sasKeyBuffer.length = 0;
        this.interpreter?.keyboardManager?.clearPressedKeys();
    }

    _mj$keyGetChar$char$(t: Thread, callback: CallbackParameter): void {
        this.ensureSASKeyboardListener();
        const character = this.sasKeyBuffer.shift();
        if (character !== undefined) {
            t.s.push(character);
            callback?.();
        } else {
            t.state = ThreadState.waiting;
            this.sasKeyWaiters.push({ thread: t, callback });
        }
    }

    private ensureSASKeyboardListener(): void {
        const keyboard = this.interpreter?.keyboardManager;
        if (this.sasKeyboardListenerRegistered || !keyboard) return;
        const events = this.interpreter.eventManager;
        const onKeyDown = (key: string, _shift: boolean, ctrl: boolean, alt: boolean) => {
            if (ctrl || alt) return;
            const character = key.length === 1 ? key
                : ({ Enter: "\n", Backspace: "\b", Tab: "\t" } as Record<string, string>)[key];
            if (character === undefined) return;
            const waiter = this.sasKeyWaiters.shift();
            if (waiter) {
                waiter.thread.s.push(character);
                waiter.thread.state = ThreadState.running;
                waiter.callback?.();
            } else {
                this.sasKeyBuffer.push(character);
            }
        };
        const cleanup = () => {
            keyboard.removeKeyDownListener(onKeyDown);
            this.sasKeyBuffer.length = 0;
            this.sasKeyWaiters.length = 0;
            this.sasKeyboardListenerRegistered = false;
            events.off(cleanup);
        };
        keyboard.addKeyDownListener(onKeyDown);
        events.on("stop", cleanup);
        events.on("resetRuntime", cleanup);
        this.sasKeyboardListenerRegistered = true;
    }

    _keyPressed(): boolean {
        return this.interpreter?.keyboardManager?.isAnyKeyPressed() ?? false;
    }

    _keyPressedChar(character: string): boolean {
        return this.interpreter?.keyboardManager?.isPressed(character) ?? false;
    }

    _keyLeftPressed(): boolean {
        return this.interpreter?.keyboardManager?.isPressed("ArrowLeft") ?? false;
    }

    _keyRightPressed(): boolean {
        return this.interpreter?.keyboardManager?.isPressed("ArrowRight") ?? false;
    }

    _keyUpPressed(): boolean {
        return this.interpreter?.keyboardManager?.isPressed("ArrowUp") ?? false;
    }

    _keyDownPressed(): boolean {
        return this.interpreter?.keyboardManager?.isPressed("ArrowDown") ?? false;
    }

    _keyEnterPressed(): boolean {
        return this.interpreter?.keyboardManager?.isPressed("Enter") ?? false;
    }

    _keyBackspacePressed(): boolean {
        return this.interpreter?.keyboardManager?.isPressed("Backspace") ?? false;
    }

    _getMouseX(): number {
        return Math.round(this.sasMouseX);
    }

    _getMouseY(): number {
        return Math.round(this.sasMouseY);
    }

    private ensureSASMouseListener() {
        if (this.sasMouseListenerRegistered || !this.mouseManager) return;

        let listener: InternalMouseListener = {
            onMouseEvent: (_kind: MouseEventKind, x: number, y: number) => {
                this.sasMouseX = x;
                this.sasMouseY = y;
            }
        };

        this.mouseManager.internalMouseListeners.push(listener);
        this.sasMouseListenerRegistered = true;
    }
}
