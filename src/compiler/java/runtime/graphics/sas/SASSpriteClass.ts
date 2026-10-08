import * as PIXI from "pixi.js";
import { CallbackParameter } from "../../../../common/interpreter/CallbackParameter";
import { CallbackFunction } from "../../../../common/interpreter/StepFunction";
import { Thread } from "../../../../common/interpreter/Thread";
import { LibraryDeclarations } from "../../../module/libraries/DeclareType";
import { NonPrimitiveType } from "../../../types/NonPrimitiveType";
import { GroupClass } from "../GroupClass";
import { ShapeClass } from "../ShapeClass";
import { SASShapesClass } from "./SASShapesClass";

export class SASSpriteClass extends GroupClass {
    static __javaDeclarations: LibraryDeclarations = [
        { type: "declaration", package: "sas", signature: "class Sprite extends sas.Shapes" },

        { type: "method", signature: "Sprite()", java: SASSpriteClass.prototype._cj$_constructor_$Sprite$ },
        { type: "method", signature: "Sprite(sas.Shapes shape)", java: SASSpriteClass.prototype._cj$_constructor_$Sprite$Shapes },
        { type: "method", signature: "void add(sas.Shapes shape)", native: SASSpriteClass.prototype.add },
        { type: "method", signature: "Color getColor()", native: SASSpriteClass.prototype._getFillColor },
        { type: "method", signature: "int getColorAsInt()", native: SASSpriteClass.prototype._getFillColorAsInt },
        { type: "method", signature: "void setColor(Color color)", native: SASSpriteClass.prototype._setFillColorColor },
        { type: "method", signature: "void setColor(int color)", native: SASSpriteClass.prototype._setFillColorInt },
        { type: "method", signature: "void setColor(string color)", native: SASSpriteClass.prototype._setFillColorString },
        { type: "method", signature: "double getShapeX()", native: SASSpriteClass.prototype._getTopLeftX },
        { type: "method", signature: "double getShapeY()", native: SASSpriteClass.prototype._getTopLeftY },
        { type: "method", signature: "double getShapeWidth()", native: SASSpriteClass.prototype._getShapeWidth },
        { type: "method", signature: "double getShapeHeight()", native: SASSpriteClass.prototype._getShapeHeight },
        { type: "method", signature: "double getCenterX()", native: SASSpriteClass.prototype._getCenterX },
        { type: "method", signature: "double getCenterY()", native: SASSpriteClass.prototype._getCenterY },
        { type: "method", signature: "double getDirection()", native: SASSpriteClass.prototype._getDirection },
        { type: "method", signature: "void setDirection(double angle)", native: SASSpriteClass.prototype._setDirection },
        { type: "method", signature: "boolean getHidden()", native: SASSpriteClass.prototype._getHidden },
        { type: "method", signature: "void setHidden(boolean hidden)", native: SASSpriteClass.prototype._setHidden },
        { type: "method", signature: "float getTransparency()", native: SASSpriteClass.prototype._getTransparency },
        { type: "method", signature: "void setTransparency(float transparency)", native: SASSpriteClass.prototype._setTransparency },
        { type: "method", signature: "void loadTexture(string filename)", java: SASSpriteClass.prototype._mj$loadTexture$void$string },
        { type: "method", signature: "void turn(double angle)", native: SASSpriteClass.prototype._turn },
        { type: "method", signature: "void turn(double x, double y, double angle)", native: SASSpriteClass.prototype._turnAround },
        { type: "method", signature: "void turnTo(double angle)", native: SASSpriteClass.prototype._turnToAngle },
        { type: "method", signature: "void turnTo(double x, double y)", native: SASSpriteClass.prototype._turnToPoint },
        { type: "method", signature: "void move(double distance)", native: SASSpriteClass.prototype._moveDistance },
        { type: "method", signature: "void move(double dx, double dy)", native: SASSpriteClass.prototype._move },
        { type: "method", signature: "void moveTo(double x, double y)", native: SASSpriteClass.prototype._moveToTopLeft },
        { type: "method", signature: "void scale(double sx, double sy)", native: SASSpriteClass.prototype._scaleXY },
        { type: "method", signature: "void scaleTo(double width, double height)", native: SASSpriteClass.prototype._scaleTo },
        { type: "method", signature: "void flipHorizontal()", native: SASSpriteClass.prototype._flipHorizontal },
        { type: "method", signature: "void flipVertical()", native: SASSpriteClass.prototype._flipVertical },
        { type: "method", signature: "void reset()", native: SASSpriteClass.prototype._reset },
        { type: "method", signature: "boolean intersects(sas.Shapes other)", native: SASSpriteClass.prototype._collidesWith },
        { type: "method", signature: "boolean contains(sas.Shapes other)", native: SASSpriteClass.prototype._containsShape },
        { type: "method", signature: "boolean contains(double x, double y)", native: SASSpriteClass.prototype._containsPoint },
        { type: "method", signature: "boolean mousePressed()", native: SASSpriteClass.prototype._mousePressed },
        { type: "method", signature: "boolean mouseDragged()", native: SASSpriteClass.prototype._mouseDragged },
        { type: "method", signature: "boolean mouseClicked()", native: SASSpriteClass.prototype._mouseClicked },
        { type: "method", signature: "int getMouseX()", native: SASSpriteClass.prototype._getMouseX },
        { type: "method", signature: "int getMouseY()", native: SASSpriteClass.prototype._getMouseY },
        { type: "method", signature: "Sprite clone()", java: SASSpriteClass.prototype._mj$clone$Sprite$ },
    ]

    static type: NonPrimitiveType;
    sasDirection: number = 90;
    private sasMouseX: number = 0;
    private sasMouseY: number = 0;
    private sasMouseDownStartedInside: boolean = false;
    private sasMousePressed: boolean = false;
    private sasMouseDragged: boolean = false;
    private sasMouseClicked: boolean = false;
    private sasMouseListenerRegistered: boolean = false;

    _registerListeners(t: Thread) {
        super._registerListeners(t);
        (this as any).ensureSASMouseListener();
    }

    _cj$_constructor_$Sprite$(t: Thread, callback: CallbackFunction) {
        this._cj$_constructor_$Group$(t, callback);
    }

    _cj$_constructor_$Sprite$Shapes(t: Thread, callback: CallbackFunction, shape: SASShapesClass | SASSpriteClass) {
        this._cj$_constructor_$Group$(t, () => {
            this.add(shape);
            if (callback) callback();
        });
    }

    get _getShapeWidth() { return SASShapesClass.prototype._getShapeWidth; }
    get _getSASBounds() { return SASShapesClass.prototype._getSASBounds; }
    get _getShapeHeight() { return SASShapesClass.prototype._getShapeHeight; }
    _getTopLeftX() { return SASShapesClass.prototype._getTopLeftX.call(this); }
    _getTopLeftY() { return SASShapesClass.prototype._getTopLeftY.call(this); }
    get _getDirection() { return SASShapesClass.prototype._getDirection; }
    get _setDirection() { return SASShapesClass.prototype._setDirection; }
    get _getHidden() { return SASShapesClass.prototype._getHidden; }
    get _setHidden() { return SASShapesClass.prototype._setHidden; }
    get _getTransparency() { return SASShapesClass.prototype._getTransparency; }
    get _setTransparency() { return SASShapesClass.prototype._setTransparency; }
    get _moveDistance() { return SASShapesClass.prototype._moveDistance; }
    get _moveToTopLeft() { return SASShapesClass.prototype._moveToTopLeft; }
    get _turn() { return SASShapesClass.prototype._turn; }
    get _turnAround() { return SASShapesClass.prototype._turnAround; }
    get _turnToAngle() { return SASShapesClass.prototype._turnToAngle; }
    get _turnToPoint() { return SASShapesClass.prototype._turnToPoint; }
    get _scaleXY() { return SASShapesClass.prototype._scaleXY; }
    get _scaleTo() { return SASShapesClass.prototype._scaleTo; }
    get _flipHorizontal() { return SASShapesClass.prototype._flipHorizontal; }
    get _flipVertical() { return SASShapesClass.prototype._flipVertical; }
    get _reset() { return SASShapesClass.prototype._reset; }
    get _containsShape() { return SASShapesClass.prototype._containsShape; }
    get _mousePressed() { return SASShapesClass.prototype._mousePressed; }
    get _mouseDragged() { return SASShapesClass.prototype._mouseDragged; }
    get _mouseClicked() { return SASShapesClass.prototype._mouseClicked; }
    get _getMouseX() { return SASShapesClass.prototype._getMouseX; }
    get _getMouseY() { return SASShapesClass.prototype._getMouseY; }
    _mj$loadTexture$void$string(t: Thread, callback: CallbackParameter, filename: string) {
        let index = 0;
        const next = () => {
            if (index === this.shapes.length) return callback?.();
            (this.shapes[index++] as SASShapesClass)._mj$loadTexture$void$string(t, next, filename);
        };
        next();
    }

    _mj$clone$Shapes$(t: Thread, callback: CallbackParameter) {
        this._mj$clone$Sprite$(t, callback);
    }
    ensureSASMouseListener = SASShapesClass.prototype["ensureSASMouseListener"];
    scaleAround = SASShapesClass.prototype["scaleAround"];

    _getFillColor() {
        return (this.shapes[0] as any)?._getFillColor?.() ?? null;
    }

    _getFillColorAsInt() {
        return this.shapes[0]?.fillColor ?? 0;
    }

    _setFillColorColor(color: any) {
        for (let shape of this.shapes) shape._setTintColor?.(color);
    }

    _setFillColorInt(color: number) {
        for (let shape of this.shapes) shape._setTintInt?.(color);
    }

    _setFillColorString(color: string) {
        for (let shape of this.shapes) shape._setTintString?.(color);
    }

    _containsPoint(x: number, y: number) {
        return SASShapesClass.prototype._containsPoint.call(this, x, y);
    }

    _collidesWith(otherShape: ShapeClass): boolean {
        return SASShapesClass.prototype._collidesWith.call(this, otherShape);
    }

    _mj$clone$Sprite$(t: Thread, callback: CallbackParameter) {
        let copy = new SASSpriteClass();
        copy._cj$_constructor_$Sprite$(t, () => {
            let index = 0;
            let cloneNext = () => {
                if (index >= this.shapes.length) {
                    copy.copyFrom(this);
                    copy.sasDirection = this.sasDirection;
                    copy.container.visible = this.container.visible;
                    copy.setWorldTransformAndHitPolygonDirty();
                    if (callback) callback();
                    return;
                }
                let shape = this.shapes[index] as any;
                let cloneMethod = shape._mj$clone$Shapes$ ?? shape._mj$copy$Shape$;
                cloneMethod.call(shape, t, () => {
                    const child = t.s.pop() as ShapeClass;
                    copy.add(child);
                    child.container.setFromMatrix(shape.container.localTransform);
                    child.container.updateLocalTransform();
                    child.setWorldTransformAndHitPolygonDirty();
                    index++;
                    cloneNext();
                });
            };
            cloneNext();
        });
    }

    render(): void {
    }

    setWorldTransformAndHitPolygonDirty(): void {
        this.worldTransformDirty = true;
        for (let shape of this.shapes) shape.setWorldTransformAndHitPolygonDirty();
    }
}
