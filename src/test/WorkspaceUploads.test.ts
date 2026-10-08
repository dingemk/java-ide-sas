import { describe, expect, it } from 'vitest';
import { readWorkspaceUpload } from '../tools/WorkspaceUploads';
import { SASIOStringFileToolsClass } from '../compiler/java/runtime/graphics/sas/SASIOStringFileToolsClass';
import { FileTypeManager } from '../compiler/common/module/FileTypeManager';
import type { Thread } from '../compiler/common/interpreter/Thread';

function file(bytes: number[] | Uint8Array, name = 'woerter.txt'): File {
    return { name, size: bytes.length, arrayBuffer: async () => new Uint8Array(bytes).buffer } as File;
}

describe('workspace text uploads', () => {
    it('stores UTF-8 as editable text and exposes lines through StringFileTools', async () => {
        const text = 'Apfel\r\nGr\u00fc\u00dfe\r\nBirne\r\n';
        const data = await readWorkspaceUpload(file(new TextEncoder().encode(text)));
        expect(data).toBe(text);
        expect(FileTypeManager.filenameToFileType('woerter.txt', undefined).language).toBe('text');
        const t = {
            s: [], scheduler: { interpreter: { fileManager: { read: (name: string) => {
                expect(name).toBe('woerter.txt');
                return data;
            } } } }
        } as unknown as Thread;
        SASIOStringFileToolsClass._mj$loadFileInStringArray$string_I$string(t, 'woerter.txt');
        expect(t.s.pop()).toEqual(['Apfel', 'Gr\u00fc\u00dfe', 'Birne']);
    });

    it('accepts empty files and uppercase extensions, and removes a UTF-8 BOM', async () => {
        expect(await readWorkspaceUpload(file([], 'LEER.TXT'))).toBe('');
        expect(await readWorkspaceUpload(file([239, 187, 191, 65]))).toBe('A');
    });

    it('rejects unsupported, oversized, non-UTF-8 and binary text files', async () => {
        await expect(readWorkspaceUpload(file([], 'a.exe'))).rejects.toThrow('TXT');
        await expect(readWorkspaceUpload({ name: 'a.txt', size: 11 * 1024 * 1024 } as File)).rejects.toThrow('10 MB');
        await expect(readWorkspaceUpload(file([255, 254, 65]))).rejects.toThrow('UTF-8');
        await expect(readWorkspaceUpload(file([65, 0, 66]))).rejects.toThrow('Binaerdaten');
    });

    it('continues validating image uploads with the existing image reader', async () => {
        await expect(readWorkspaceUpload({ name: 'a.png', size: 11 * 1024 * 1024 } as File)).rejects.toThrow('10 MB');
    });
});
