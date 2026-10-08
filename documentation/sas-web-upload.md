# SAS-Java-IDE hochladen

1. `sas-java-ide-web.zip` entpacken.
2. Den gesamten Inhalt in einen Ordner auf dem Webserver hochladen, zum Beispiel `/java-ide/`.
3. `https://DEINE-DOMAIN/java-ide/` im Browser öffnen. Die Demo öffnet ein SAS-Beispiel in der IDE.

Dies ist eine statische Webversion. PHP, Java, Node und eine Benutzeranmeldung sind auf dem Server nicht erforderlich. Java wird im Browser ausgeführt. Über HTTP oder HTTPS öffnen, nicht per Doppelklick als lokale Datei.

## Blockly verbinden

Die Blockly-Seite mit der Adresse der hochgeladenen IDE aufrufen:

```text
https://DEINE-DOMAIN/sasblockly/index.html?ideBase=https://DEINE-DOMAIN/java-ide
```

Alternativ in der Blockly-`index.html` den Standardwert von `JAVA_IDE_BASE_URL` von `http://localhost:3000` auf `https://DEINE-DOMAIN/java-ide` ändern. Der Button öffnet dann `/java-ide/sas-book-demo/runner.html` mit dem aktuellen Programm.

Alle Unterordner unverändert mit hochladen. Insbesondere `assets/` enthält JavaScript, Schriftarten und Editor-Worker. `.js` als JavaScript ausliefern und fehlende Dateien nicht auf eine andere HTML-Seite umleiten. HTTPS wird empfohlen.

## Bilder und Textdateien

Das Icon "Bilder und Textdateien laden (PNG/JPG/TXT)" in der IDE nimmt lokale
Bilder und UTF-8-Textdateien bis 10 MB pro Datei auf. Diese werden im Workspace gespeichert und beim JSON-Export
mitgenommen. Sie muessen nicht separat auf den Server hochgeladen werden.

TXT-Dateien lassen sich im Editor bearbeiten und mit
`StringFileTools.loadFileInStringArray("woerter.txt")` aus `sasio` lesen.
Jede Zeile entspricht einem Array-Eintrag. Unter `sas-book-demo/examples/`
liegen `TextdateiLadenTest.java` und die passende Datei `woerter.txt`.

SAS-Bilder wie `boote.jpg` in `sas-book-demo/assets/` hochladen. `new Picture(100, 50, 400, 300, "boote.jpg")` lädt das Bild dort. Das Boote-Bild ist im Paket enthalten.

## Neue Funktionen

Kreise verwenden nach Drehungen ihre tatsaechlichen sichtbaren Grenzen statt
des gedrehten Grafik-Begrenzungsrechtecks. Dadurch setzt `moveTo(60, 500)` den
Torwand-Ball auch nach `turnTo(...)` exakt zurueck. Beispiel:
`sas-book-demo/examples/TorwandRuecksetzenTest.java`.

`View.keyGetChar()` wartet auf das naechste Zeichen und entfernt es aus dem
Tastaturpuffer. `keyBufferDelete()` leert Zeichenpuffer und gedrueckte Tasten.
Enter liefert `\n`, Backspace `\b` und Tab `\t`; Pfeil- und Funktionstasten
werden weiterhin ueber die vorhandenen Tastenzustandsmethoden abgefragt.
Beispiel: `sas-book-demo/examples/KeyGetCharTest.java`.

`Tools.getHour()`, `Tools.getMinute()` und `Tools.getSecond()` liefern die
aktuelle lokale Uhrzeit des Browsers, mit Stunde 0-23 und Minute/Sekunde 0-59.

Die eingebettete IDE aktiviert standardmaessig SAS und das gesamte vorhandene
NRW-Modul (`libraries: ["sas", "nrw"]`). Verfuegbar sind `List`, `Stack`,
`Queue`, `BinaryTree`, `BinarySearchTree`, `ComparableContent`, `Graph`,
`Vertex`, `Edge`, `DatabaseConnector` und `QueryResult`.
`import nrw.*;` ist moeglich; das NRW-Modul importiert seine Typen auch automatisch.
Beim Workspace-Import bleiben konfigurierte Bibliotheken aktiviert.
Das Beispiel `sas-book-demo/examples/NRWBibliothekTest.java` prueft Datenstrukturen,
Baeume und Graphen zusammen mit SAS Tools.

`Tools.inputDialog(String)` ist verfuegbar: OK/Enter liefert den eingegebenen
String, Abbrechen/Escape liefert `null`. `import sas.Tools;` wird jetzt korrekt
aufgeloest; `import sas.*;` funktioniert weiterhin. Ein Testprogramm liegt unter
`sas-book-demo/examples/ToolsInputDialogTest.java`.

Die Webversion enthaelt die aktuellen SAS-Korrekturen fuer Polygon,
Sprite-Drehung, Klone, Texturen und moveTo nach Drehungen, Tools.stringToDouble
sowie die vier sasio-Klassen Button, Label, Textfield und StringFileTools.
Tools.getDirection, message und confirmDialog sowie Text.setFontSansSerif,
setFontSerif und setFontMonospaced sind ebenfalls enthalten. confirmDialog
liefert 0 fuer Ja, 1 fuer Nein und -1 beim Schliessen oder Escape.
Neue StringFileTools-Dateien liegen im Sitzungsspeicher, nicht auf dem Server.
Picture-Kollisionen beruecksichtigen jetzt transparente Bildbereiche und Loecher.
Polygon.contains prueft auch bei konkaven Konturen die gesamte andere Flaeche.
Details und die CORS-Grenze stehen in `documentation/sas-collisions.md` im Quelltext.

In der Buchdemo kann "Zur IDE hinzufuegen" eine benannte Java-Datei in den
bestehenden Workspace einfuegen, ohne vorhandene Dateien oder Bilder zu
loeschen. Das Schliessen des Modals erhaelt den Workspace. Neuladen der
Buchseite verwirft diese Sitzung; fuer dauerhafte Speicherung als JSON exportieren.

Testseiten nach dem Upload: `sas-book-demo/sasio-test.html` und
`sas-book-demo/windpark-test.html` sowie `sas-book-demo/alpha-kollision-test.html`.
Weitere Java-Beispiele liegen in
`sas-book-demo/examples/`.

## Quelltext und Herkunft

Die Online-IDE stammt von Martin Pabst: https://github.com/martin-pabst/Online-IDE-new-compiler

Die Projektlizenz liegt in `LICENSE`, Paketlizenzen in `THIRD-PARTY-LICENSES.txt`. Der zugehörige Quelltext inklusive SAS und Build-Konfiguration liegt in `quelltext.zip`. Diese Dateien zusammen mit der Webversion hochladen.

Neu bauen nach dem Entpacken von `quelltext.zip`:

```sh
npm ci
npm run build-sas-web
```

Das fertige Verzeichnis heißt `dist-sas-web/`, das Upload-Archiv `upload/sas-java-ide-web.zip`.
Der entpackte Upload liegt ebenfalls in `upload/java-ide/`.
Beim Aktualisieren die gesamte neue Version hochladen, nicht nur index.html.
Nach dem Upload die Buchdemo und IDE im Browser vollstaendig neu laden.
