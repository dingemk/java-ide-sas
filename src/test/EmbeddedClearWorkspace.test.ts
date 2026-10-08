import { afterEach, describe, expect, it, vi } from 'vitest';
import { MainEmbedded } from '../client/embedded/MainEmbedded';

function fixture() {
    const files = [{ name: 'Main.java', readOnly: false }, { name: 'foto.png', readOnly: false }];
    const editor = { setModel: vi.fn(), updateOptions: vi.fn() };
    const compiler = { setFiles: vi.fn(), triggerCompile: vi.fn() };
    const hint = { empty: vi.fn(), hide: vi.fn() };
    hint.empty.mockReturnValue(hint);
    const main = {
        currentWorkspace: { readonly: false, getFiles: () => files, removeAllFiles: vi.fn(() => files.splice(0)) },
        interpreter: { isRunningOrPaused: () => true, stop: vi.fn(), resetRuntime: vi.fn() },
        indexedDB: { removeScript: vi.fn(), writeScript: vi.fn() },
        config: { id: 'test-' },
        lastActiveFile: files[0],
        fileExplorer: { removeAllFiles: vi.fn() },
        $hintDiv: hint,
        $monacoDiv: { show: vi.fn() },
        getMainEditor: () => editor,
        getCompiler: () => compiler,
        showResetButton: vi.fn()
    };
    return { main, files, editor, compiler, clear: () => MainEmbedded.prototype.clearWorkspace.call(main) };
}

afterEach(() => {
    vi.unstubAllGlobals();
    vi.useRealTimers();
});

describe('embedded workspace clear', () => {
    it('leaves everything untouched when cancelled', () => {
        vi.stubGlobal('confirm', vi.fn(() => false));
        const f = fixture();
        f.clear();
        expect(f.files).toHaveLength(2);
        expect(f.main.interpreter.stop).not.toHaveBeenCalled();
        expect(f.main.indexedDB.writeScript).not.toHaveBeenCalled();
        expect(f.editor.setModel).not.toHaveBeenCalled();
    });

    it('removes code and images, stops execution, empties the editor and persists an empty list', () => {
        vi.stubGlobal('confirm', vi.fn(() => true));
        const f = fixture();
        f.clear();
        expect(f.files).toHaveLength(0);
        expect(f.main.interpreter.stop).toHaveBeenCalledWith(false);
        expect(f.main.interpreter.resetRuntime).toHaveBeenCalledOnce();
        expect(f.main.indexedDB.removeScript).toHaveBeenCalledWith('test-Main.java');
        expect(f.main.indexedDB.removeScript).toHaveBeenCalledWith('test-foto.png');
        expect(f.main.indexedDB.writeScript).toHaveBeenCalledWith('test-', '[]');
        expect(f.main.lastActiveFile).toBeUndefined();
        expect(f.editor.setModel).toHaveBeenCalledWith(null);
        expect(f.main.fileExplorer.removeAllFiles).toHaveBeenCalledOnce();
        expect(f.compiler.setFiles).toHaveBeenCalledWith([]);
        expect(f.compiler.triggerCompile).toHaveBeenCalledOnce();
    });

    it('protects read-only files', () => {
        const confirm = vi.fn(() => true);
        vi.stubGlobal('confirm', confirm);
        vi.stubGlobal('alert', vi.fn());
        const f = fixture();
        f.files[0].readOnly = true;
        f.clear();
        expect(confirm).not.toHaveBeenCalled();
        expect(f.files).toHaveLength(2);
    });

    it('works without local storage or a file list', () => {
        vi.stubGlobal('confirm', vi.fn(() => true));
        const f = fixture();
        Object.assign(f.main, { indexedDB: undefined, fileExplorer: undefined });
        expect(f.clear).not.toThrow();
        expect(f.files).toHaveLength(0);
    });

    it('finishes restoring a saved empty workspace instead of waiting forever', async () => {
        vi.useFakeTimers();
        const f = fixture();
        for (const file of f.files) Object.assign(file, { getMonacoModel: vi.fn(), setSaved: vi.fn() });
        Object.assign(f.main.config, { cacheUserEdits: true });
        Object.assign(f.main.fileExplorer, { removeFile: vi.fn() });
        Object.assign(f.main.indexedDB, { getScript: (_id: string, callback: (text: string) => void) => callback('[]') });
        await MainEmbedded.prototype.readScriptsFromIndexedDB.call(f.main);
        expect(f.files).toHaveLength(0);
        expect(f.editor.setModel).toHaveBeenCalledWith(null);
        expect(f.main.lastActiveFile).toBeUndefined();
        expect(f.main.showResetButton).toHaveBeenCalledOnce();
    });
});
