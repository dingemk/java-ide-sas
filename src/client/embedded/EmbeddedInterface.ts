import type { IThrowable } from "../../compiler/common/interpreter/ThrowableType";
import type { RuntimeExceptionClass } from "../../compiler/java/runtime/system/javalang/RuntimeException";
import type { GUIFile } from "../workspace/File";
import type { MainEmbedded } from "./MainEmbedded";

export type ExitStatus = {
    exitCode: number;
    output: string;
    exception?: IThrowable;
    testProgress?: TestProgress;   
}

export type OnRunExitListener = (exitStatus: ExitStatus) => void;

interface IDEFileAccess {
    getName(): string;
    getText(): string;
}

interface IDEStatusAccess {
    registerOnRunExitListener(listener: OnRunExitListener): void;
}

interface SingleIDEAccess {
    getFiles(): IDEFileAccess[];
    addJavaFile(name: string, text: string): void;
    registerOnRunExitListener(listener: OnRunExitListener): void;
}

interface OnlineIDEAccess {
    getIDE(id: string): SingleIDEAccess | undefined;
}


/** Implementation classes */

export class IDEFileAccessImpl implements IDEFileAccess {
    constructor(private file: GUIFile){

    }

    getName(): string {
        return this.file.name;
    }
    getText(): string {
        return this.file.getText();
    }

    
}

export class SingleIDEAccessImpl implements SingleIDEAccess {

    constructor(private ide: MainEmbedded, private enableFileAccess: boolean, private enableRunExitStatusAccess: boolean, private enableFileWriteAccess: boolean = false){

    }

    getFiles(): IDEFileAccess[] {
        if(!this.enableFileAccess){
            throw new Error("File access is not enabled for this IDE");
        }
        return this.ide.getCurrentWorkspace().getFiles().map(file => new IDEFileAccessImpl(file));        
    }

    addJavaFile(name: string, text: string): void {
        if (!this.enableFileWriteAccess) throw new Error("File write access is not enabled for this IDE");
        if (!/^[\p{ID_Start}_$][\p{ID_Continue}$]*\.java$/u.test(name)) {
            throw new Error("Bitte einen gueltigen Java-Dateinamen angeben, z.B. Ufo.java.");
        }
        if (typeof text !== "string") throw new Error("Kein Java-Quelltext uebergeben.");
        const workspace = this.ide.getCurrentWorkspace();
        if (workspace.readonly) throw new Error("Der Workspace ist schreibgeschuetzt.");
        if (workspace.getFiles().some(file => file.name === name)) {
            throw new Error(`Die Datei ${name} existiert bereits. Bitte einen anderen Namen waehlen.`);
        }
        if (this.ide.interpreter.isRunningOrPaused()) this.ide.interpreter.stop(false);
        const file = this.ide.addFile({ title: name, text });
        file.setSaved(false);
        if (this.ide.fileExplorer) {
            this.ide.fileExplorer.addFile(file);
            this.ide.fileExplorer.selectFile(file);
        } else {
            this.ide.setFileActive(file);
        }
        this.ide.getCompiler().setFiles(workspace.getFiles());
        this.ide.getCompiler().triggerCompile();
        this.ide.showResetButton();
        if (this.ide.indexedDB) this.ide.saveScripts();
    }

    registerOnRunExitListener(listener: OnRunExitListener): void {
        if(!this.enableRunExitStatusAccess){
            throw new Error("Run exit status access is not enabled for this IDE");
        }
        this.ide.registerOnRunExitListener(listener);
    }

}

export class OnlineIDEAccessImpl implements OnlineIDEAccess {
    
    private static  ideMap: Map<string, SingleIDEAccessImpl> = new Map();

    public static registerIDE(ide: MainEmbedded){
        OnlineIDEAccessImpl.ideMap.set(ide.config.id!,  
            new SingleIDEAccessImpl(ide, ide.config.enableFileAccess ?? false, ide.config.enableRunExitStatusAccess ?? false, ide.config.enableFileWriteAccess ?? false));
        window.dispatchEvent(new CustomEvent("online-ide-ready", { detail: { id: ide.config.id } }));
    }
    
    getIDE(id: string): SingleIDEAccess | undefined {
        return OnlineIDEAccessImpl.ideMap.get(id);
    }

}
