import * as PIXI from "pixi.js";
import { CallbackParameter } from "../../../../common/interpreter/CallbackParameter";
import { CallbackFunction } from "../../../../common/interpreter/StepFunction";
import { Thread } from "../../../../common/interpreter/Thread";
import { LibraryDeclarations } from "../../../module/libraries/DeclareType";
import { NonPrimitiveType } from "../../../types/NonPrimitiveType";
import { SASShapesClass } from "./SASShapesClass";
import { resolveSASImageURL } from "./SASImageLoader";
import { ThreadState } from "../../../../common/interpreter/ThreadState";
import { alphaToArea } from "./SASCollision";
import type { MultiPolygon } from "polygon-clipping";

const alphaAreas = new WeakMap<PIXI.Texture, Map<string, MultiPolygon>>();

export class SASPictureClass extends SASShapesClass {
    static __javaDeclarations: LibraryDeclarations = [
        { type: "declaration", package: "sas", signature: "class Picture extends sas.Shapes" },

        { type: "method", signature: "Picture(double x, double y, string filename)", java: SASPictureClass.prototype._cj$_constructor_$Picture$double$double$string },
        { type: "method", signature: "Picture(double x, double y, double width, double height, string filename)", java: SASPictureClass.prototype._cj$_constructor_$Picture$double$double$double$double$string },
        { type: "method", signature: "Picture clone()", java: SASPictureClass.prototype._mj$clone$Picture$ },
    ]

    static type: NonPrimitiveType;

    left!: number;
    top!: number;
    filename!: string;
    texture?: PIXI.Texture;
    imageURL?: string;
    sasCollisionArea?: MultiPolygon;

    _cj$_constructor_$Picture$double$double$string(t: Thread, callback: CallbackFunction, x: number, y: number, filename: string) {
        this.initPicture(t, callback, x, y, 100, 100, filename, true);
    }

    _cj$_constructor_$Picture$double$double$double$double$string(t: Thread, callback: CallbackFunction, x: number, y: number, width: number, height: number, filename: string) {
        this.initPicture(t, callback, x, y, width, height, filename);
    }

    private initPicture(t: Thread, callback: CallbackFunction, left: number, top: number, width: number, height: number, filename: string, intrinsicSize = false) {
        this._cj$_constructor_$FilledShape$(t, () => {
            this.left = left;
            this.top = top;
            this.initialWidth = width;
            this.initialHeight = height;
            this.filename = filename;
            this.centerXInitial = left + width / 2;
            this.centerYInitial = top + height / 2;
            this.fillColor = undefined;
            this.borderColor = undefined;
            this.imageURL = resolveSASImageURL(t, filename);
            const previousState = t.state;
            t.state = ThreadState.waiting;
            PIXI.Assets.load(this.imageURL)
                .then((texture: PIXI.Texture) => {
                    this.texture = texture;
                    if (intrinsicSize) {
                        this.initialWidth = texture.orig.width;
                        this.initialHeight = texture.orig.height;
                        this.centerXInitial = left + this.initialWidth / 2;
                        this.centerYInitial = top + this.initialHeight / 2;
                    }
                    this.buildCollisionArea();
                    this.render();
                })
                .catch(() => {
                    this.renderMissingImagePlaceholder();
                })
                .finally(() => {
                    if (t.state !== ThreadState.waiting) return;
                    t.state = previousState;
                    if (callback) callback();
                });
        });
    }

    private buildCollisionArea(): void {
        const width = Math.max(1, Math.ceil(Math.abs(this.initialWidth)));
        const height = Math.max(1, Math.ceil(Math.abs(this.initialHeight)));
        try {
            const texture = this.texture!;
            const key = `${width}:${height}`;
            const sizes = alphaAreas.get(texture) ?? new Map<string, MultiPolygon>();
            let area = sizes.get(key);
            if (!area) {
                const canvas = document.createElement("canvas");
                canvas.width = width;
                canvas.height = height;
                const context = canvas.getContext("2d", { willReadFrequently: true });
                if (!context) throw new Error("Canvas-Kontext nicht verfuegbar");
                const resolution = texture.source.resolution;
                const frame = texture.frame;
                context.drawImage(texture.source.resource as CanvasImageSource,
                    frame.x * resolution, frame.y * resolution, frame.width * resolution, frame.height * resolution,
                    0, 0, width, height);
                area = alphaToArea(context.getImageData(0, 0, width, height).data, width, height);
                sizes.set(key, area);
                alphaAreas.set(texture, sizes);
            }
            this.sasCollisionArea = area.map(polygon => polygon.map(ring => ring.map(([x, y]) => [
                this.left + x * this.initialWidth / width,
                this.top + y * this.initialHeight / height
            ])));
        } catch (error) {
            this.sasCollisionArea = undefined;
            console.warn("SAS Picture: Alphamaske nicht lesbar; rechteckige Kollisionsflaeche wird verwendet.", error);
        }
    }

    render(): void {
        this.updateHitPolygon();

        let container = this.ensurePictureContainer();
        container.removeChildren();
        container.alpha = this.fillAlpha;

        if (this.texture) {
            let sprite = new PIXI.Sprite(this.texture);
            sprite.x = this.left;
            sprite.y = this.top;
            sprite.width = this.initialWidth;
            sprite.height = this.initialHeight;
            if (this.fillColor != null) {
                sprite.tint = this.fillColor;
            }
            container.addChild(sprite);
        } else {
            this.addMissingImageGraphics(container);
        }
    }

    private updateHitPolygon(): void {
        this.hitPolygonInitial = [
            { x: this.left, y: this.top },
            { x: this.left, y: this.top + this.initialHeight },
            { x: this.left + this.initialWidth, y: this.top + this.initialHeight },
            { x: this.left + this.initialWidth, y: this.top }
        ];
        this.hitPolygonDirty = true;
    }

    private ensurePictureContainer(): PIXI.Container {
        if (!this.container) {
            this.container = new PIXI.Container();
            this.world.app.stage.addChild(this.container);
        }
        return this.container;
    }

    private renderMissingImagePlaceholder(): void {
        this.texture = undefined;
        this.sasCollisionArea = undefined;
        this.render();
    }

    private addMissingImageGraphics(container: PIXI.Container): void {
        let g = new PIXI.Graphics();
        g.rect(this.left, this.top, this.initialWidth, this.initialHeight);

        g.fill(0xd8dde6);
        g.stroke({
            width: this.borderWidth,
            color: 0x79808a,
            alpha: this.borderAlpha,
            alignment: 0.5
        });
        g.moveTo(this.left, this.top);
        g.lineTo(this.left + this.initialWidth, this.top + this.initialHeight);
        g.moveTo(this.left + this.initialWidth, this.top);
        g.lineTo(this.left, this.top + this.initialHeight);
        g.stroke({
            width: 1,
            color: 0x79808a,
            alpha: this.borderAlpha,
            alignment: 0.5
        });
        g.closePath();
        container.addChild(g);
    }

    _mj$clone$Picture$(t: Thread, callback: CallbackParameter) {
        let copy = new SASPictureClass();
        copy._cj$_constructor_$Picture$double$double$double$double$string(t, () => {
            copy.copyFrom(this);
            copy.render();
            if (callback) callback();
        }, this.left, this.top, this.initialWidth, this.initialHeight, this.filename);
    }
}
