import { describe, expect, it } from "vitest";
import { isWorkspaceImage, isWorkspaceImageData, readWorkspaceImage } from "../tools/WorkspaceImages";
import { resolveSASImageURL } from "../compiler/java/runtime/graphics/sas/SASImageLoader";
import { FileNotFoundExceptionClass } from "../compiler/java/runtime/system/javalang/FileNotFoundException";
import type { Thread } from "../compiler/common/interpreter/Thread";
import { FileTypeManager } from "../compiler/common/module/FileTypeManager";

const data = 'data:image/png;base64,iVBORw0KGgo=';
function thread(read: (name: string) => string): Thread {
    return { scheduler: { interpreter: { fileManager: { read }, retrieveObject: () => 'https://example.org/assets/' } } } as unknown as Thread;
}
describe('workspace images', () => {
    it('recognizes image extensions and excludes images from Java compilation', () => {
        for (const name of ['boote.png', 'Boote.JPG', 'boote.jpeg']) {
            expect(isWorkspaceImage(name)).toBe(true);
            expect(FileTypeManager.filenameToFileType(name, undefined).language).toBe('text');
        }
        expect(isWorkspaceImage('Test.java')).toBe(false);
        expect(isWorkspaceImageData(data)).toBe(true);
        expect(isWorkspaceImageData('data:image/svg+xml;base64,abcd')).toBe(false);
    });
    it('prefers embedded workspace data and preserves external asset resolution', () => {
        expect(resolveSASImageURL(thread(() => data), 'boote.png')).toBe(data);
        expect(resolveSASImageURL(thread(() => { throw new FileNotFoundExceptionClass('boote.jpg'); }), 'boote.jpg')).toBe('https://example.org/assets/boote.jpg');
        expect(resolveSASImageURL(thread(() => 'invalid'), 'boote.png')).toBe('https://example.org/assets/boote.png');
        expect(resolveSASImageURL(thread(() => { throw new Error('must not read'); }), 'https://example.org/a.png')).toBe('https://example.org/a.png');
    });
    it('rejects unsupported files, oversized images and wrong file signatures', async () => {
        await expect(readWorkspaceImage({ name: 'a.svg', size: 1 } as File)).rejects.toThrow('PNG');
        await expect(readWorkspaceImage({ name: 'a.png', size: 11 * 1024 * 1024 } as File)).rejects.toThrow('10 MB');
        await expect(readWorkspaceImage({ name: 'a.png', size: 3, slice: () => ({ arrayBuffer: async () => new Uint8Array([1, 2, 3]).buffer }) } as unknown as File)).rejects.toThrow('kein PNG');
    });
});
