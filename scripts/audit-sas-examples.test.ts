import { writeFileSync } from 'node:fs';
import { basename, dirname, resolve } from 'node:path';
import AdmZip from 'adm-zip';
import { expect, test } from 'vitest';
import '../src/compiler/common/interpreter/Interpreter';
import { JavaCompiler } from '../src/compiler/java/JavaCompiler';
import { JavaLibraryManager } from '../src/compiler/java/runtime/JavaLibraryManager';
import { CompilerFile } from '../src/compiler/common/module/CompilerFile';

// Optional diagnostic run; never executes the examples' interactive or infinite loops.
const archivePath = process.env.SAS_EXAMPLES_ZIP;

test.skipIf(!archivePath)('audit compilation of the supplied SAS examples', async () => {
    const archive = new AdmZip(archivePath!);
    const projects = new Map<string, CompilerFile[]>();
    for (const entry of archive.getEntries()) {
        if (!entry.entryName.endsWith('.java') || entry.entryName.startsWith('__MACOSX/')) continue;
        const project = dirname(entry.entryName);
        const file = new CompilerFile(basename(entry.entryName));
        file.setText(archive.readAsText(entry).replace(/\r\n/g, '\n'));
        projects.set(project, [...(projects.get(project) ?? []), file]);
    }

    const report = [];
    for (const [project, files] of projects) {
        const compiler = new JavaCompiler();
        const libraries = new JavaLibraryManager();
        libraries.addLibraries('sas');
        libraries.addLibrariesToCompiler(compiler);
        compiler.setFiles(files);
        const executable = await compiler.compileIfDirty();
        expect(executable, project).toBeDefined();
        const errors = executable!.getAllErrors().filter(error => error.level === 'error');
        report.push({ project, files: files.map(file => file.name), errors });
        console.log(`${project}: ${errors.length} errors`);
        for (const error of errors) console.log(`  ${error.range.startLineNumber}: ${error.message}`);
    }
    const output = resolve('documentation/sas-example-compile-audit.json');
    writeFileSync(output, JSON.stringify(report, null, 2) + '\n');
    console.log(`Compilation only: ${report.filter(row => row.errors.length === 0).length}/${report.length} projects without errors. Report: ${output}`);
}, 60000);
