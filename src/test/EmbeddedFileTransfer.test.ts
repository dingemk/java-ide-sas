import { afterEach, describe, expect, it, vi } from 'vitest';
import { readFileSync } from 'node:fs';
import { JSDOM } from 'jsdom';
import { SingleIDEAccessImpl } from '../client/embedded/EmbeddedInterface';
import type { MainEmbedded } from '../client/embedded/MainEmbedded';

function fixture(enabled = true) {
    const files = [{ name: 'Main.java' }, { name: 'boote.jpg' }];
    const workspace = { readonly: false, getFiles: () => files };
    const compiler = { setFiles: vi.fn(), triggerCompile: vi.fn() };
    const ide = {
        getCurrentWorkspace: () => workspace,
        interpreter: { isRunningOrPaused: () => true, stop: vi.fn() },
        addFile: vi.fn(({ title, text }) => {
            const file = { name: title, text, setSaved: vi.fn() };
            files.push(file);
            return file;
        }),
        fileExplorer: { addFile: vi.fn(), selectFile: vi.fn() },
        getCompiler: () => compiler,
        showResetButton: vi.fn()
    };
    return { files, workspace, compiler, ide, access: new SingleIDEAccessImpl(ide as unknown as MainEmbedded, false, false, enabled) };
}

describe('embedded Java file transfer', () => {
    it('adds a named class without losing source or images, then selects and compiles it', () => {
        const f = fixture();
        const originals = f.files.slice();
        f.access.addJavaFile('Ufo.java', 'class Ufo {}');
        expect(f.files.slice(0, 2)).toEqual(originals);
        expect(f.files[2].name).toBe('Ufo.java');
        expect(f.ide.interpreter.stop).toHaveBeenCalledWith(false);
        expect(f.ide.fileExplorer.selectFile).toHaveBeenCalledWith(f.files[2]);
        expect(f.compiler.setFiles).toHaveBeenCalledWith(f.files);
        expect(f.compiler.triggerCompile).toHaveBeenCalledOnce();
    });
    it('rejects duplicates, bad filenames and read-only workspaces without making changes', () => {
        const f = fixture();
        expect(() => f.access.addJavaFile('Main.java', 'other')).toThrow('existiert bereits');
        expect(() => f.access.addJavaFile('../Ufo.java', 'other')).toThrow('Java-Dateinamen');
        f.workspace.readonly = true;
        expect(() => f.access.addJavaFile('Ufo.java', 'other')).toThrow('schreibgeschuetzt');
        expect(f.ide.addFile).not.toHaveBeenCalled();
        expect(f.ide.interpreter.stop).not.toHaveBeenCalled();
    });
    it('requires a separate write opt-in even when read access is available', () => {
        const f = fixture(false);
        const readOnlyAccess = new SingleIDEAccessImpl(f.ide as unknown as MainEmbedded, true, false);
        expect(() => readOnlyAccess.addJavaFile('Ufo.java', 'class Ufo {}')).toThrow('write access');
        expect(f.ide.addFile).not.toHaveBeenCalled();
    });
});

let dom: JSDOM | undefined;
afterEach(() => { dom?.window.close(); dom = undefined; });
describe('book demo transfer controls', () => {
    it('keeps the iframe on close and appends through an origin-checked message instead of reloading', () => {
        dom = new JSDOM(readFileSync('sas-book-demo/index.html', 'utf8'), { url: 'http://localhost:3000/sas-book-demo/index.html', runScripts: 'dangerously' });
        const w = dom.window;
        const doc = w.document;
        const frame = doc.querySelector<HTMLIFrameElement>('#ideFrame')!;
        doc.querySelector<HTMLButtonElement>('#openIde')!.click();
        const originalURL = frame.src;
        doc.querySelector<HTMLButtonElement>('#closeIde')!.click();
        expect(frame.src).toBe(originalURL);
        doc.querySelector<HTMLInputElement>('#filename')!.value = 'Ufo';
        doc.querySelector<HTMLTextAreaElement>('#source')!.value = 'class Ufo {}';
        const send = vi.spyOn(frame.contentWindow!, 'postMessage');
        doc.querySelector<HTMLButtonElement>('#addToIde')!.click();
        expect(frame.src).toBe(originalURL);
        expect(doc.querySelector<HTMLInputElement>('#filename')!.value).toBe('Ufo.java');
        w.dispatchEvent(new w.MessageEvent('message', { source: frame.contentWindow, origin: 'https://evil.example', data: { type: 'sas-book:ready' } }));
        expect(send.mock.calls.some(([message]) => message.type === 'sas-book:add-file')).toBe(false);
        w.dispatchEvent(new w.MessageEvent('message', { source: frame.contentWindow, origin: 'http://localhost:3000', data: { type: 'sas-book:ready' } }));
        const message = send.mock.calls.find(([message]) => message.type === 'sas-book:add-file')![0];
        expect(message.filename).toBe('Ufo.java');
        expect(message.code).toBe('class Ufo {}');
        w.dispatchEvent(new w.MessageEvent('message', { source: frame.contentWindow, origin: 'http://localhost:3000', data: { type: 'sas-book:added', requestId: message.requestId } }));
        expect(doc.querySelector('#transferStatus')!.textContent).toContain('Ufo.java hinzugefuegt');
        expect(doc.querySelector<HTMLButtonElement>('#addToIde')!.disabled).toBe(false);
    });
});
