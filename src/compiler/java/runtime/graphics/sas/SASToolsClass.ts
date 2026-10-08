import { LibraryDeclarations } from "../../../module/libraries/DeclareType";
import { NonPrimitiveType } from "../../../types/NonPrimitiveType";
import { CallbackParameter } from "../../../../common/interpreter/CallbackParameter";
import { Thread } from "../../../../common/interpreter/Thread";
import { ObjectClass } from "../../system/javalang/ObjectClassStringClass";
import { IllegalArgumentExceptionClass } from "../../system/javalang/IllegalArgumentException";
import { showSASDialog } from "./SASDialogs";

export class SASToolsClass extends ObjectClass {
    static __javaDeclarations: LibraryDeclarations = [
        { type: "declaration", package: "sas", signature: "class Tools extends Object" },

        { type: "method", signature: "Tools()", java: SASToolsClass.prototype._cj$_constructor_$Tools$ },
        { type: "method", signature: "static int randomNumber(int min, int max)", native: SASToolsClass._randomNumber },
        { type: "method", signature: "static double stringToDouble(string value)", native: SASToolsClass._stringToDouble },
        { type: "method", signature: "static double getDirection(double x, double y, double targetX, double targetY)", native: SASToolsClass._getDirection },
        { type: "method", signature: "static void message(string message, string title)", java: SASToolsClass._mj$message$void$string$string },
        { type: "method", signature: "static int confirmDialog(string message)", java: SASToolsClass._mj$confirmDialog$int$string },
        { type: "method", signature: "static string inputDialog(string message)", java: SASToolsClass._mj$inputDialog$string$string },
        { type: "method", signature: "static int getHour()", native: SASToolsClass._getHour },
        { type: "method", signature: "static int getMinute()", native: SASToolsClass._getMinute },
        { type: "method", signature: "static int getSecond()", native: SASToolsClass._getSecond },
        { type: "method", signature: "static long getStartTime()", native: SASToolsClass._getStartTime },
        { type: "method", signature: "static long getStopTime()", native: SASToolsClass._getStopTime },
        { type: "method", signature: "static float getStopTime(long startTime)", native: SASToolsClass._getStopTimeFromStart },
        { type: "method", signature: "static string getStopTimeString()", native: SASToolsClass._getStopTimeString },
        { type: "method", signature: "static string getStopTimeString(long startTime)", native: SASToolsClass._getStopTimeStringFromStart },
        { type: "method", signature: "static long getElapsedTime()", native: SASToolsClass._getStopTime },
        { type: "method", signature: "static float getElapsedTime(long startTime)", native: SASToolsClass._getStopTimeFromStart },
        { type: "method", signature: "static string getElapsedTimeString()", native: SASToolsClass._getStopTimeString },
        { type: "method", signature: "static string getElapsedTimeString(long startTime)", native: SASToolsClass._getStopTimeStringFromStart },
    ]

    static type: NonPrimitiveType;
    private static startTime = Date.now();

    _cj$_constructor_$Tools$(t: Thread, callback: CallbackParameter) {
        t.s.push(this);
        if (callback) callback();
    }

    static _randomNumber(min: number, max: number): number {
        let low = Math.ceil(Math.min(min, max));
        let high = Math.floor(Math.max(min, max));
        return Math.floor(Math.random() * (high - low + 1)) + low;
    }

    static _getStartTime(): number {
        SASToolsClass.startTime = Date.now();
        return SASToolsClass.startTime;
    }

    static _getHour(): number {
        return new Date().getHours();
    }

    static _getMinute(): number {
        return new Date().getMinutes();
    }

    static _getSecond(): number {
        return new Date().getSeconds();
    }

    static _getDirection(x: number, y: number, targetX: number, targetY: number): number {
        const angle = 90 + Math.atan((targetY - y) / (targetX - x)) * 180 / Math.PI;
        return targetX < x ? angle + 180 : angle;
    }

    static _mj$message$void$string$string(t: Thread, message: string, title: string): void {
        showSASDialog(t, message, title, false);
    }

    static _mj$confirmDialog$int$string(t: Thread, message: string): void {
        showSASDialog(t, message, "Bestaetigung", true);
    }

    static _mj$inputDialog$string$string(t: Thread, message: string): void {
        showSASDialog(t, message, "Eingabe", "input");
    }

    static _stringToDouble(value: string): number {
        const text = value.replace(/^[\x00-\x20]+|[\x00-\x20]+$/g, "");
        if (/^[+-]?(NaN|Infinity)$/.test(text)) return Number(text.replace(/^\+/, "").replace(/^[+-]NaN$/, "NaN"));
        if (/^[+-]?(?:\d+(?:\.\d*)?|\.\d+)(?:[eE][+-]?\d+)?[fFdD]?$/.test(text)) {
            return Number(text.replace(/[fFdD]$/, ""));
        }
        const hex = /^([+-]?)0[xX]([\da-fA-F]+(?:\.[\da-fA-F]*)?|\.[\da-fA-F]+)[pP]([+-]?\d+)[fFdD]?$/.exec(text);
        if (hex) {
            const [whole, fraction = ""] = hex[2].split(".");
            const mantissa = parseInt(whole || "0", 16) + (fraction ? parseInt(fraction, 16) / 16 ** fraction.length : 0);
            return (hex[1] === "-" ? -1 : 1) * mantissa * 2 ** Number(hex[3]);
        }
        throw new IllegalArgumentExceptionClass(`Cannot convert "${value}" to double.`);
    }

    static _getStopTime(): number {
        return Math.floor((Date.now() - SASToolsClass.startTime) / 1000);
    }

    static _getStopTimeFromStart(startTime: number): number {
        return (Date.now() - startTime) / 1000;
    }

    static _getStopTimeString(): string {
        return `${SASToolsClass._getStopTime()}`;
    }

    static _getStopTimeStringFromStart(startTime: number): string {
        return `${SASToolsClass._getStopTimeFromStart(startTime).toFixed(1)}`;
    }
}
