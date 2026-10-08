# Java-Dateien aus der Buchdemo hinzufuegen

`sas-book-demo/index.html` hat ein Dateinamenfeld und zwei IDE-Aktionen:

- "In IDE oeffnen" startet die IDE mit einem neuen Workspace und der benannten Datei.
- "Zur IDE hinzufuegen" fuegt die benannte Java-Datei in den vorhandenen
  Modal-Workspace ein. Existiert noch keine IDE, wird die erste Datei geoeffnet.

Die Endung `.java` wird bei Bedarf ergaenzt. Vorhandene Dateien werden nicht
ueberschrieben; bei Namenskonflikten erscheint eine Meldung. Quelltexte und
Bilder im Workspace bleiben erhalten. Ein laufendes Programm wird beim
Hinzufuegen gestoppt, die neue Datei ausgewaehlt und der Workspace neu kompiliert.

Schliessen blendet das Modal nur aus. Erst ein erneuter Klick auf "In IDE
oeffnen" ersetzt den Workspace. Neuladen der Buchseite verwirft weiterhin die
Modal-Sitzung; fuer dauerhafte Speicherung den Workspace als JSON exportieren.

Die Uebertragung nutzt `postMessage` mit festem Ziel-Origin, Pruefung von
Absenderfenster und Origin sowie einer Bestaetigung mit Request-ID. Der Runner
aktiviert dafuer ausdruecklich `enableFileWriteAccess`. Bestehendes
`enableFileAccess` allein erlaubt weiterhin keine Schreibzugriffe.
Bei anderer IDE-Basis muessen auch Runner und Embedded-IDE diesen Stand haben.

Die Aenderungen sind lokal. Das Upload-Paket wurde noch nicht neu gebaut.
