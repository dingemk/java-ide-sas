import { CodeFragment } from "../../../../common/disassembler/CodeFragment";
import { JavaLibraryModule } from "../../../module/libraries/JavaLibraryModule";
import { SASCircleClass } from "./SASCircleClass";
import { SASEllipseClass } from "./SASEllipseClass";
import { SASPictureClass } from "./SASPictureClass";
import { SASRectangleClass } from "./SASRectangleClass";
import { SASShapesClass } from "./SASShapesClass";
import { SASSpriteClass } from "./SASSpriteClass";
import { SASTextClass } from "./SASTextClass";
import { SASToolsClass } from "./SASToolsClass";
import { SASPolygonClass } from "./SASPolygonClass";
import { SASViewClass } from "./SASViewClass";
import { SASIOButtonClass } from "./SASIOButtonClass";
import { SASIOLabelClass } from "./SASIOLabelClass";
import { SASIOTextfieldClass } from "./SASIOTextfieldClass";
import { SASIOStringFileToolsClass } from "./SASIOStringFileToolsClass";

export class SASModule extends JavaLibraryModule {
    constructor() {
        super();
        this.classesInterfacesEnums.push(
            SASViewClass,
            SASShapesClass,
            SASRectangleClass,
            SASCircleClass,
            SASEllipseClass,
            SASPictureClass,
            SASPolygonClass,
            SASTextClass,
            SASSpriteClass,
            SASToolsClass,
            SASIOButtonClass,
            SASIOLabelClass,
            SASIOTextfieldClass,
            SASIOStringFileToolsClass,
        );
    }

    isReplModule(): boolean {
        return false;
    }

    getStandardImports(): string[][] {
        // Preserve the previously implicit Tools name while supporting sas.Tools.
        return [["sas", "Tools"]];
    }

    getCodeFragments(): CodeFragment[] {
        return [];
    }
}
