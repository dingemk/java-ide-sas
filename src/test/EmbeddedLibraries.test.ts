import { afterEach, describe, expect, it, vi } from 'vitest';
import { readFileSync } from 'node:fs';
import { MainEmbedded } from '../client/embedded/MainEmbedded';

afterEach(() => vi.unstubAllGlobals());

describe('embedded SAS and NRW libraries', () => {
    it('activates both libraries in the book runner and standalone embedded page', () => {
        expect(readFileSync('sas-book-demo/runner.html', 'utf8')).toContain('libraries: ["sas", "nrw"]');
        expect(readFileSync('embedded_java.html', 'utf8')).toContain("'libraries': ['sas', 'nrw']");
    });

    it('preserves uploaded libraries and adds configured defaults to older workspaces', async () => {
        let onload: (event: unknown) => Promise<void>;
        vi.stubGlobal('FileReader', class {
            set onload(callback: (event: unknown) => Promise<void>) { onload = callback; }
            readAsText() {}
        });
        const compiler = { setLibraries: vi.fn(), triggerCompile: vi.fn() };
        const main = {
            config: { libraries: ['sas', 'nrw'] },
            getCompiler: () => compiler,
            fileExplorer: { removeAllFiles: vi.fn(), selectFirstFileIfPresent: vi.fn() },
            saveScripts: vi.fn(), showResetButton: vi.fn(), currentWorkspace: undefined
        };
        MainEmbedded.prototype.loadWorkspaceFromFile.call(main, {} as File);
        await onload({ target: { result: JSON.stringify({ name: 'Alt', modules: [], settings: { libraries: ['sas', 'gng'] } }) } });
        expect(compiler.setLibraries).toHaveBeenCalledWith(['sas', 'gng', 'nrw']);
        expect(compiler.triggerCompile).toHaveBeenCalledOnce();
    });
});
