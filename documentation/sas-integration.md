# SAS-Bibliothek in die Online-IDE integrieren

Diese Anleitung beschreibt, wie die Shapes-and-Sprites-Bibliothek `sas.*` in die Online-IDE eingebaut werden kann. Sie richtet sich an den Maintainer der Online-IDE, weil eine neue Java-Bibliothek nicht nur als Benutzerdatei geladen werden kann: Klassen, Methoden und Runtime-Verhalten muessen im TypeScript-Code der IDE registriert und anschliessend mitgebaut werden.

## Ziel

Nach der Integration sollen Java-Programme in der Online-IDE z. B. Folgendes kompilieren und ausfuehren koennen:

```java
import sas.*;
import java.awt.Color;

View view = new View(600, 400, "Boote");
Rectangle rahmen = new Rectangle(70, 20, 459, 359, Color.BLUE);
Picture bild = new Picture(100, 50, 400, 300, "boote.jpg");

System.exit(0);
```

Im Embedded-Modus wird die Bibliothek mit der vorhandenen Konfiguration aktiviert:

```html
<div class="java-online" data-java-online="{
  'libraries': ['sas'],
  'pictureBaseURL': '/sas-book-demo/assets/'
}">
```

## Dateien der SAS-Bibliothek

Die SAS-Klassen liegen in:

```text
src/compiler/java/runtime/graphics/sas/
```

Benötigt werden diese Dateien:

```text
SASModule.ts
SASViewClass.ts
SASShapesClass.ts
SASRectangleClass.ts
SASCircleClass.ts
SASEllipseClass.ts
SASPolygonClass.ts
SASTextClass.ts
SASSpriteClass.ts
SASPictureClass.ts
SASToolsClass.ts
```

Wenn die SAS-Erweiterung in einem separaten GitHub-Repository gepflegt wird, sollte dieses Verzeichnis unverändert in den Zielbaum der Online-IDE kopiert oder per Subtree/Submodule synchronisiert werden.

## Registrierung im Library-Manager

In `src/compiler/java/runtime/JavaLibraryManager.ts` muss das Modul importiert werden:

```ts
import { SASModule } from "./graphics/sas/SASModule";
```

Dann wird die Bibliothek in `libraries` eingetragen:

```ts
{
    identifier: 'Shapes and Sprites',
    description: 'Java-kompatibler Kern der Shapes-and-Sprites-Bibliothek',
    id: 'sas'
}
```

Und in `getAdditionalModules()` wird sie aktiviert, wenn die Workspace-Einstellung `sas` enthält:

```ts
case "sas": additionalModules.push(new SASModule());
    break;
```

Damit ist `libraries: ['sas']` im Workspace bzw. Embedded-Config ausreichend.

## Kompatibilitaet mit `java.awt.Color`

Viele SAS-Beispiele importieren:

```java
import java.awt.Color;
```

Die Online-IDE besitzt bereits eine eigene `Color`-Runtimeklasse. Damit Schulbuchprogramme unveraendert laufen, wird `java.awt.Color` beim Import auf diese bestehende `Color`-Klasse abgebildet.

In `src/compiler/java/TypeResolver/TypeResolver.ts` wird beim Registrieren der Imports ergaenzt:

```ts
if (importPath.join(".") == "java.awt.Color") {
    importPath = ["Color"];
}
```

Zusaetzlich sollten in `src/compiler/java/runtime/graphics/ColorClass.ts` auch die grossgeschriebenen AWT-Konstanten verfuegbar sein, also z. B. `Color.RED` neben `Color.red`. Das kann beim Initialisieren der vordefinierten Farben durch Uppercase-Aliase geschehen.

## Bilder fuer `Picture`

`Picture` lädt Bilder ueber PIXI aus einer URL. Relative Dateinamen wie

```java
new Picture(100, 50, 400, 300, "boote.jpg");
```

werden gegen eine optionale Embedded-Konfiguration aufgeloest:

```ts
pictureBaseURL?: string
```

In `src/client/embedded/MainEmbedded.ts` wird dieser Wert nach Erzeugen des Interpreters in der Runtime abgelegt:

```ts
this.interpreter.storeObject("SASPictureBaseURL", this.config.pictureBaseURL ?? "");
```

`SASPictureClass.ts` verwendet diesen Wert. Ein `pictureBaseURL` wie `/sas-book-demo/assets/` wird dabei gegen die aktuelle Browser-URL aufgeloest, sodass bei lokalem Test mit Port 3000 daraus z. B. wird:

```text
http://localhost:3000/sas-book-demo/assets/boote.jpg
```

Fuer Schulbuchbeispiele ist es daher am einfachsten, Bilder serverseitig auszuliefern, z. B.:

```text
sas-book-demo/assets/boote.jpg
sas-book-demo/assets/chagall.png
sas-book-demo/assets/landschaft.jpg
```

Ein Benutzer-Upload ist dafuer nicht erforderlich.

## Embedded-Beispiel

Eine einfache Embedded-Seite kann so konfiguriert werden:

```html
<div class="java-online" data-java-online="{
  'id': 'sas-example',
  'speed': 'max',
  'withFileList': true,
  'hideEditor': false,
  'hideStartPanel': false,
  'libraries': ['sas'],
  'pictureBaseURL': '/sas-book-demo/assets/',
  'withClassDiagram': true
}">
  <script type="text/plain" title="Program.java" data-type="java">
    import sas.*;
    import java.awt.Color;

    View view = new View(600, 400, "Boote");
    Rectangle aussen = new Rectangle(70, 20, 459, 359, Color.BLUE);
    Rectangle innen = new Rectangle(100, 50, 399, 299, Color.white);
    Picture bild = new Picture(100, 50, 400, 300, "boote.jpg");

    System.exit(0);
  </script>
</div>
```

## Tests

Es gibt einen Kompilationstest fuer die SAS-Kernklassen:

```text
src/test/java/SASTest.java
```

Der Test enthaelt im Kopf die benoetigte Bibliothekseinstellung:

```java
/**::
 * Test SAS library core types
 * {"libraries": ["sas"]}
 */
```

Empfohlene Checks vor einem Pull Request:

```bash
npx tsc --noEmit
npx vitest --config vitest.config.ts run src/test/JavaTests.test.ts
npm run build
```

## Abgrenzung

Ein normaler Benutzer der Online-IDE kann `sas.*` nicht selbst nachtraeglich im Browser installieren. Die Bibliothek muss in die IDE eingebaut und mit der IDE ausgeliefert werden. Danach kann sie pro Workspace oder Embedded-Instanz einfach ueber `libraries: ['sas']` aktiviert werden.

