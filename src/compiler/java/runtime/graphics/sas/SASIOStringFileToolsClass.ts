import type { Interpreter } from "../../../../common/interpreter/Interpreter";
import { Thread } from "../../../../common/interpreter/Thread";
import { LibraryDeclarations } from "../../../module/libraries/DeclareType";
import { NonPrimitiveType } from "../../../types/NonPrimitiveType";
import { ObjectClass } from "../../system/javalang/ObjectClassStringClass";

export class SASIOStringFileToolsClass extends ObjectClass {
    static __javaDeclarations: LibraryDeclarations = [
        { type: "declaration", package: "sasio", signature: "class StringFileTools extends Object" },
        { type: "method", signature: "StringFileTools()", native: SASIOStringFileToolsClass.prototype._construct },
        { type: "method", signature: "static boolean fileExists(string filename)", java: SASIOStringFileToolsClass._mj$fileExists$boolean$string },
        { type: "method", signature: "static string[] loadFileInStringArray(string filename)", java: SASIOStringFileToolsClass._mj$loadFileInStringArray$string_I$string },
        { type: "method", signature: "static string loadFileInString(string filename)", java: SASIOStringFileToolsClass._mj$loadFileInString$string$string },
        { type: "method", signature: "static int countFileRows(string filename)", java: SASIOStringFileToolsClass._mj$countFileRows$int$string },
        { type: "method", signature: "static void writeInFile(string filename, string text)", java: SASIOStringFileToolsClass._mj$writeInFile$void$string$string },
        { type: "method", signature: "static void appendToFile(string filename, string text)", java: SASIOStringFileToolsClass._mj$appendToFile$void$string$string },
    ];
    static type: NonPrimitiveType;
    // Newly created files live for this IDE session, separate from the host filesystem.
    private static files = new WeakMap<Interpreter, Map<string, string>>();

    _construct(): void {}

    private static storage(t: Thread): Map<string, string> {
        const interpreter = t.scheduler.interpreter;
        let files = this.files.get(interpreter);
        if (!files) { files = new Map(); this.files.set(interpreter, files); }
        return files;
    }
    private static read(t: Thread, filename: string): string | null {
        const virtual = this.storage(t).get(filename);
        if (virtual !== undefined) return virtual;
        try { return t.scheduler.interpreter.fileManager?.read(filename) ?? null; }
        catch { return null; }
    }
    private static rows(text: string): string[] {
        if (!text) return [];
        const lines = text.split(/\r\n|\r|\n/);
        if (/[\r\n]$/.test(text)) lines.pop();
        return lines;
    }
    private static write(t: Thread, filename: string, text: string): void {
        const manager = t.scheduler.interpreter.fileManager;
        let workspaceFile = false;
        if (manager && !this.storage(t).has(filename)) {
            try { manager.read(filename); workspaceFile = true; } catch { /* New virtual file. */ }
        }
        if (workspaceFile) manager!.write(filename, text);
        else this.storage(t).set(filename, text);
    }
    static _mj$fileExists$boolean$string(t: Thread, filename: string): void {
        t.s.push(this.read(t, filename) !== null);
    }
    static _mj$loadFileInStringArray$string_I$string(t: Thread, filename: string): void {
        const text = this.read(t, filename);
        t.s.push(text === null ? null : this.rows(text));
    }
    static _mj$loadFileInString$string$string(t: Thread, filename: string): void {
        const text = this.read(t, filename);
        t.s.push(text === null ? null : this.rows(text).join("\n"));
    }
    static _mj$countFileRows$int$string(t: Thread, filename: string): void {
        const text = this.read(t, filename);
        t.s.push(text === null ? -1 : this.rows(text).length);
    }
    static _mj$writeInFile$void$string$string(t: Thread, filename: string, text: string): void {
        this.write(t, filename, text + "\n");
    }
    static _mj$appendToFile$void$string$string(t: Thread, filename: string, text: string): void {
        this.write(t, filename, (this.read(t, filename) ?? "") + text + "\n");
    }
}
