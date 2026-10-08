import { Thread } from "../../../../common/interpreter/Thread";
import { isWorkspaceImage, isWorkspaceImageData } from "../../../../../tools/WorkspaceImages";
import { FileNotFoundExceptionClass } from "../../system/javalang/FileNotFoundException";

export function resolveSASImageURL(t: Thread, filename: string): string {
    if (/^(https?:|data:|blob:|\/)/i.test(filename)) return filename;
    if (isWorkspaceImage(filename)) {
        try {
            const data = t.scheduler.interpreter.fileManager?.read(filename);
            if (data && isWorkspaceImageData(data)) return data;
        } catch (error) {
            if (!(error instanceof FileNotFoundExceptionClass)) throw error;
        }
    }
    const base = t.scheduler.interpreter.retrieveObject("SASPictureBaseURL") as string | undefined;
    if (!base) return filename;
    const absoluteBase = new URL(base.endsWith("/") ? base : `${base}/`, globalThis.location?.href);
    return new URL(filename, absoluteBase).toString();
}
