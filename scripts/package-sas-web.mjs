import { cpSync, existsSync, mkdirSync, readdirSync, readFileSync, writeFileSync } from 'node:fs';
import { resolve } from 'node:path';
import AdmZip from 'adm-zip';

const root = resolve(import.meta.dirname, '..');
const output = resolve(root, 'dist-sas-web');
const archiveFolder = resolve(root, 'upload');
mkdirSync(archiveFolder, { recursive: true });
cpSync(resolve(root, 'sas-book-demo/assets'), resolve(output, 'sas-book-demo/assets'), { recursive: true });
cpSync(resolve(root, 'sas-book-demo/examples'), resolve(output, 'sas-book-demo/examples'), { recursive: true });
for (const page of ['sasio-test.html', 'windpark-test.html', 'alpha-kollision-test.html']) {
    cpSync(resolve(root, 'sas-book-demo', page), resolve(output, 'sas-book-demo', page));
}
cpSync(resolve(root, 'LICENSE'), resolve(output, 'LICENSE'));
cpSync(resolve(root, 'sas-online-ide-dingemk/LICENSE.md'), resolve(output, 'SAS-RECHTE.md'));
cpSync(resolve(root, 'documentation/sas-web-upload.md'), resolve(output, 'UPLOAD-ANLEITUNG.md'));
writeFileSync(resolve(output, 'index.html'), `<!doctype html>
<html lang="de"><head><meta charset="utf-8"><meta http-equiv="refresh" content="0;url=./sas-book-demo/index.html"><title>SAS-Java-IDE</title></head>
<body><a href="./sas-book-demo/index.html">SAS-Java-IDE öffnen</a> · <a href="./sas-book-demo/lizenzen.html">Lizenzen und Quelltext</a></body></html>\n`);

const sourceArchive = new AdmZip();
for (const folder of ['src', 'assets', 'public', 'sas-book-demo', 'documentation', 'scripts']) {
    sourceArchive.addLocalFolder(resolve(root, folder), folder);
}
for (const entry of readdirSync(root, { withFileTypes: true })) {
    if (entry.isFile() && (/\.(html|ts|json)$/.test(entry.name) || ['LICENSE', 'README.md', '.npmrc', '.gitignore'].includes(entry.name))) {
        sourceArchive.addLocalFile(resolve(root, entry.name));
    }
}
sourceArchive.addLocalFile(resolve(root, 'sas-online-ide-dingemk/LICENSE.md'), 'sas-online-ide-dingemk');
sourceArchive.writeZip(resolve(output, 'quelltext.zip'));

const notices = ['Online-IDE von Martin Pabst mit SAS-Erweiterung.',
    'SAS und SASBlockly: Copyright net-schulbuch.de gUG (haftungsbeschränkt).',
    'Originalprojekt: https://github.com/martin-pabst/Online-IDE-new-compiler',
    'Projektlizenz: GNU GPL v3 (siehe LICENSE).',
    'Der Quelltext dieser Version liegt in quelltext.zip.', '', 'Lizenzen eingebundener npm-Pakete:', ''];
function collectLicenses(directory, prefix = '') {
    for (const entry of readdirSync(directory, { withFileTypes: true })) {
        if (!entry.isDirectory() || entry.name.startsWith('.')) continue;
        const path = resolve(directory, entry.name);
        if (entry.name.startsWith('@')) {
            collectLicenses(path, entry.name + '/');
            continue;
        }
        const license = readdirSync(path).find(name => /^licen[sc]e(?:\.|$)/i.test(name));
        if (license && existsSync(resolve(path, license))) {
            notices.push(`--- ${prefix}${entry.name} ---`, readFileSync(resolve(path, license), 'utf8'), '');
        }
    }
}
collectLicenses(resolve(root, 'node_modules'));
writeFileSync(resolve(output, 'THIRD-PARTY-LICENSES.txt'), notices.join('\n'));
const uploadArchive = new AdmZip();
uploadArchive.addLocalFolder(output);
uploadArchive.writeZip(resolve(archiveFolder, 'sas-java-ide-web.zip'));
cpSync(output, resolve(archiveFolder, 'java-ide'), { recursive: true });
console.log('Upload-Paket: upload/sas-java-ide-web.zip');
console.log('Entpackter Upload: upload/java-ide/');
