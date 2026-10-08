import { isWorkspaceImage, readWorkspaceImage } from './WorkspaceImages';

export async function readWorkspaceUpload(file: File): Promise<string> {
    if (isWorkspaceImage(file.name)) return readWorkspaceImage(file);
    if (!/\.txt$/i.test(file.name)) {
        throw new Error('Bitte eine PNG-, JPG- oder TXT-Datei auswaehlen.');
    }
    if (file.size > 10 * 1024 * 1024) {
        throw new Error('Die Textdatei darf maximal 10 MB gross sein.');
    }
    const bytes = await file.arrayBuffer();
    let text: string;
    try {
        text = new TextDecoder('utf-8', { fatal: true }).decode(bytes);
    } catch {
        throw new Error('Die Textdatei muss als UTF-8 gespeichert sein.');
    }
    if (text.includes('\0')) throw new Error('Die Datei enthaelt Binaerdaten statt Text.');
    return text;
}
