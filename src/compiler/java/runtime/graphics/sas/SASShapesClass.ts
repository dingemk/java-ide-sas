import * as PIXI from "pixi.js";
import { CallbackParameter } from "../../../../common/interpreter/CallbackParameter";
import { resolveSASImageURL } from "./SASImageLoader";
import { ColorHelper } from "../../../lexer/ColorHelper";
import { ThreadState } from "../../../../common/interpreter/ThreadState";
import { Thread } from "../../../../common/interpreter/Thread";
import { LibraryDeclarations } from "../../../module/libraries/DeclareType";
import { NonPrimitiveType } from "../../../types/NonPrimitiveType";
import { ColorClass } from "../ColorClass";
import { FilledShapeClass } from "../FilledShapeClass";
import { InternalMouseListener, MouseEventKind } from "../MouseManager2D";
import { containsSAS, containsSASPoint, intersectsSAS } from "./SASCollision";
import type { ShapeClass } from "../ShapeClass";

export class SASShapesClass extends FilledShapeClass {
    static __javaDeclarations: LibraryDeclarations = [
        { type: "declaration", package: "sas", signature: "abstract class Shapes extends FilledShape" },

        { type: "method", signature: "double getShapeX()", native: SASShapesClass.prototype._getTopLeftX },
        { type: "method", signature: "double getShapeY()", native: SASShapesClass.prototype._getTopLeftY },
        { type: "method", signature: "double getShapeWidth()", native: SASShapesClass.prototype._getShapeWidth },
        { type: "method", signature: "double getShapeHeight()", native: SASShapesClass.prototype._getShapeHeight },
        { type: "method", signature: "double getCenterX()", native: SASShapesClass.prototype._getCenterX },
        { type: "method", signature: "double getCenterY()", native: SASShapesClass.prototype._getCenterY },
        { type: "method", signature: "Color getColor()", native: SASShapesClass.prototype._getFillColor },
        { type: "method", signature: "int getColorAsInt()", native: SASShapesClass.prototype._getFillColorAsInt },
        { type: "method", signature: "void setColor(Color color)", native: SASShapesClass.prototype._setFillColorColor },
        { type: "method", signature: "void setColor(int color)", native: SASShapesClass.prototype._setFillColorInt },
        { type: "method", signature: "void setColor(string color)", native: SASShapesClass.prototype._setFillColorString },
        { type: "method", signature: "double getDirection()", native: SASShapesClass.prototype._getDirection },
        { type: "method", signature: "void setDirection(double angle)", native: SASShapesClass.prototype._setDirection },
        { type: "method", signature: "boolean getHidden()", native: SASShapesClass.prototype._getHidden },
        { type: "method", signature: "void setHidden(boolean hidden)", native: SASShapesClass.prototype._setHidden },
        { type: "method", signature: "float getTransparency()", native: SASShapesClass.prototype._getTransparency },
        { type: "method", signature: "void setTransparency(float transparency)", native: SASShapesClass.prototype._setTransparency },
        { type: "method", signature: "void loadTexture(string filename)", java: SASShapesClass.prototype._mj$loadTexture$void$string },
        { type: "method", signature: "void turn(double angle)", native: SASShapesClass.prototype._turn },
        { type: "method", signature: "void turn(double x, double y, double angle)", native: SASShapesClass.prototype._turnAround },
        { type: "method", signature: "void turnTo(double angle)", native: SASShapesClass.prototype._turnToAngle },
        { type: "method", signature: "void turnTo(double x, double y)", native: SASShapesClass.prototype._turnToPoint },
        { type: "method", signature: "void move(double distance)", native: SASShapesClass.prototype._moveDistance },
        { type: "method", signature: "void move(double dx, double dy)", native: SASShapesClass.prototype._move },
        { type: "method", signature: "void moveTo(double x, double y)", native: SASShapesClass.prototype._moveToTopLeft },
        { type: "method", signature: "void scale(double sx, double sy)", native: SASShapesClass.prototype._scaleXY },
        { type: "method", signature: "void scaleTo(double width, double height)", native: SASShapesClass.prototype._scaleTo },
        { type: "method", signature: "void flipHorizontal()", native: SASShapesClass.prototype._flipHorizontal },
        { type: "method", signature: "void flipVertical()", native: SASShapesClass.prototype._flipVertical },
        { type: "method", signature: "void reset()", native: SASShapesClass.prototype._reset },
        { type: "method", signature: "Shapes clone()", java: SASShapesClass.prototype._mj$clone$Shapes$ },
        { type: "method", signature: "boolean intersects(Shapes other)", native: SASShapesClass.prototype._collidesWith },
        { type: "method", signature: "boolean contains(Shapes other)", native: SASShapesClass.prototype._containsShape },
        { type: "method", signature: "boolean contains(double x, double y)", native: SASShapesClass.prototype._containsPoint },
        { type: "method", signature: "boolean mousePressed()", native: SASShapesClass.prototype._mousePressed },
        { type: "method", signature: "boolean mouseDragged()", native: SASShapesClass.prototype._mouseDragged },
        { type: "method", signature: "boolean mouseClicked()", native: SASShapesClass.prototype._mouseClicked },
        { type: "method", signature: "int getMouseX()", native: SASShapesClass.prototype._getMouseX },
        { type: "method", signature: "int getMouseY()", native: SASShapesClass.prototype._getMouseY },
    ]

    static type: NonPrimitiveType;
    sasDirection: number = 90;
    sasTexture?: PIXI.Texture;
    private sasColor?: ColorClass;

    _getFillColor(): ColorClass {
        if (this.fillColor == null) return null;
        if (!this.sasColor || this.sasColor._toInt() !== this.fillColor) {
            this.sasColor = new ColorClass()._constructorColorClass1(
                (this.fillColor >> 16) & 255,
                (this.fillColor >> 8) & 255,
                this.fillColor & 255,
                this.fillAlpha
            );
        }
        return this.sasColor;
    }

    _setFillColorColor(color: ColorClass, alpha?: number) {
        // SAS programs compare getColor() with the original Color using ==.
        this.sasColor = color;
        return super._setFillColorColor(color, alpha);
    }

    _setFillColorInt(color: number) {
        this.sasColor = undefined;
        return super._setFillColorInt(color);
    }

    _setFillColorString(color: string) {
        this.sasColor = undefined;
        return super._setFillColorString(color);
    }

    copyFrom(other: SASShapesClass): void {
        super.copyFrom(other);
        this.sasDirection = other.sasDirection;
        this.sasTexture = other.sasTexture;
        this.sasColor = other.sasColor;
        this.container.visible = other.container.visible;
        this.setWorldTransformAndHitPolygonDirty();
    }

    protected fillGraphics(g: PIXI.Graphics): void {
        if (this.sasTexture) {
            g.fill({ texture: this.sasTexture, matrix: new PIXI.Matrix() });
        } else if (this.fillColor != null) {
            g.fill(this.fillColor);
        }
        g.alpha = this.fillAlpha;
    }
    private sasMouseX: number = 0;
    private sasMouseY: number = 0;
    private sasMouseDownStartedInside: boolean = false;
    private sasMousePressed: boolean = false;
    private sasMouseDragged: boolean = false;
    private sasMouseClicked: boolean = false;
    private sasMouseListenerRegistered: boolean = false;

    _registerListeners(t: Thread) {
        super._registerListeners(t);
        this.ensureSASMouseListener();
    }

    _getShapeWidth(): number {
        return this._getSASBounds().width;
    }

    _getSASBounds(): { minX: number; minY: number; width: number; height: number } {
        return this.container.getBounds();
    }

    _getTopLeftX(): number {
        return this._getSASBounds().minX;
    }

    _getTopLeftY(): number {
        return this._getSASBounds().minY;
    }

    _getShapeHeight(): number {
        return this._getSASBounds().height;
    }

    _getDirection(): number {
        return this.sasDirection;
    }

    _setDirection(angle: number): void {
        this.sasDirection = ((angle % 360) + 360) % 360;
    }

    _getHidden(): boolean {
        return !this.container.visible;
    }

    _setHidden(hidden: boolean): void {
        this._setVisible(!hidden);
    }

    _getTransparency(): number {
        return Math.round((1 - this.fillAlpha) * 100);
    }

    _setTransparency(transparency: number): void {
        let alpha = 1 - Math.max(0, Math.min(100, transparency)) / 100;
        this.fillAlpha = alpha;
        this.borderAlpha = alpha;
        this.render();
    }

    _moveDistance(distance: number): void {
        let angle = this.sasDirection / 180 * Math.PI;
        this._move(distance * Math.sin(angle), -distance * Math.cos(angle));
    }

    _moveToTopLeft(x: number, y: number): void {
        this._move(x - this._getTopLeftX(), y - this._getTopLeftY());
    }

    _turn(angle: number): void {
        // SAS angles increase clockwise; the underlying graphics API uses counterclockwise angles.
        this._rotate(-angle);
        this.sasDirection = ((this.sasDirection + angle) % 360 + 360) % 360;
    }

    _turnAround(x: number, y: number, angle: number): void {
        this._rotate(-angle, x, y);
    }

    _turnToAngle(angle: number): void {
        this._turn(angle - this.sasDirection);
        this._setDirection(angle);
    }

    _turnToPoint(x: number, y: number): void {
        let dx = x - this._getCenterX();
        let dy = y - this._getCenterY();
        let angle = (90 - Math.atan2(-dy, dx) * 180 / Math.PI + 360) % 360;
        this._turnToAngle(angle);
    }

    _scaleXY(sx: number, sy: number): void {
        this.scaleAround(this._getCenterX(), this._getCenterY(), sx, sy);
    }

    _scaleTo(width: number, height: number): void {
        let currentWidth = this._getShapeWidth() || 1;
        let currentHeight = this._getShapeHeight() || 1;
        this._scaleXY(width / currentWidth, height / currentHeight);
    }

    _flipHorizontal(): void {
        this._scaleXY(-1, 1);
    }

    _flipVertical(): void {
        this._scaleXY(1, -1);
    }

    _reset(): void {
        this.container.setFromMatrix(new PIXI.Matrix());
        this.container.updateLocalTransform();
        //@ts-ignore
        this.container._didLocalTransformChangeId = this.container._didChangeId;
        this.angle = 0;
        this.scaleFactor = 1;
        this.sasDirection = 90;
        this.setWorldTransformAndHitPolygonDirty();
    }

    _containsShape(other: SASShapesClass): boolean { return containsSAS(this, other); }

    _collidesWith(other: ShapeClass): boolean { return intersectsSAS(this, other); }

    _containsPoint(x: number, y: number): boolean { return containsSASPoint(this, x, y); }

    _mousePressed(): boolean {
        this.ensureSASMouseListener();
        return this.sasMousePressed;
    }

    _mouseDragged(): boolean {
        this.ensureSASMouseListener();
        return this.sasMouseDragged;
    }

    _mouseClicked(): boolean {
        this.ensureSASMouseListener();
        let clicked = this.sasMouseClicked;
        this.sasMouseClicked = false;
        return clicked;
    }

    _getMouseX(): number {
        this.ensureSASMouseListener();
        return Math.round(this.sasMouseX);
    }

    _getMouseY(): number {
        this.ensureSASMouseListener();
        return Math.round(this.sasMouseY);
    }

    _mj$loadTexture$void$string(t: Thread, callback: CallbackParameter, filename: string): void {
        const url = resolveSASImageURL(t, filename);
        const previousState = t.state;
        t.state = ThreadState.waiting;
        PIXI.Assets.load<PIXI.Texture>(url).then(texture => {
            if (!this.isDestroyed) {
                this.sasTexture = texture;
                this.render();
            }
        }).catch(() => {
            // Like SAS, an unavailable texture leaves the existing fill unchanged.
        }).finally(() => {
            if (t.state !== ThreadState.waiting) return;
            t.state = previousState;
            callback?.();
        });
    }

    protected _setFillColorOrTextureString(t: Thread, callback: CallbackParameter, colorOrFilename: string): void {
        const isColor = colorOrFilename == null || ColorHelper.predefinedColors[colorOrFilename] != null
            || /^#(?:[0-9a-f]{6}|[0-9a-f]{8})$/i.test(colorOrFilename)
            || /^rgba?\s*\(/i.test(colorOrFilename);
        if (!isColor) {
            this._mj$loadTexture$void$string(t, callback, colorOrFilename);
            return;
        }
        this._setFillColorString(colorOrFilename);
        callback?.();
    }

    protected ensureSASMouseListener(): void {
        if (this.sasMouseListenerRegistered || !this.world?.mouseManager) return;

        let listener: InternalMouseListener = {
            onMouseEvent: (kind: MouseEventKind, x: number, y: number) => {
                const pointerMoved = x !== this.sasMouseX || y !== this.sasMouseY;
                this.sasMouseX = x;
                this.sasMouseY = y;

                let containsPointer = !!this.container?.visible && this._containsPoint(x, y);

                switch (kind) {
                    case "mousedown":
                        this.sasMouseDownStartedInside = containsPointer;
                        this.sasMousePressed = containsPointer;
                        this.sasMouseDragged = false;
                        this.sasMouseClicked = false;
                        break;
                    case "mousemove":
                        if (this.sasMouseDownStartedInside && this.sasMousePressed && pointerMoved) {
                            this.sasMouseDragged = true;
                        }
                        break;
                    case "mouseup":
                        if (this.sasMouseDownStartedInside && containsPointer && !this.sasMouseDragged) {
                            this.sasMouseClicked = true;
                        }
                        this.sasMouseDownStartedInside = false;
                        this.sasMousePressed = false;
                        this.sasMouseDragged = false;
                        break;
                    case "mouseleave":
                        this.sasMouseDownStartedInside = false;
                        this.sasMousePressed = false;
                        this.sasMouseDragged = false;
                        break;
                }
            }
        };

        this.world.mouseManager.internalMouseListeners.push(listener);
        this.sasMouseListenerRegistered = true;
    }

    _mj$clone$Shapes$(t: Thread, callback: CallbackParameter) {
        const declarations = (this.constructor as typeof SASShapesClass).__javaDeclarations;
        const clone = declarations.find(d => d.type === "method" && /\bclone\(\)/.test(d.signature));
        if (clone?.type === "method" && clone.java && clone.java !== SASShapesClass.prototype._mj$clone$Shapes$) {
            clone.java.call(this, t, callback);
        } else {
            throw new Error("SAS clone: no concrete implementation");
        }
    }

    protected scaleAround(x: number, y: number, sx: number, sy: number) {
        let p = new PIXI.Point(x, y);
        this.getWorldTransform().applyInverse(p, p);
        this.container.localTransform.apply(p, p);

        this.container.localTransform.translate(-p.x, -p.y);
        this.container.localTransform.scale(sx, sy);
        this.container.localTransform.translate(p.x, p.y);

        this.container.setFromMatrix(this.container.localTransform);
        this.container.updateLocalTransform();
        //@ts-ignore
        this.container._didLocalTransformChangeId = this.container._didChangeId;

        this.setWorldTransformAndHitPolygonDirty();
        this.scaleFactor *= Math.max(Math.abs(sx), Math.abs(sy));
    }
}
