# sasio in der Browser-IDE

Die SAS-Bibliothek registriert jetzt auch das Paket `sasio`.
Programme verwenden `import sas.*;`, `import sasio.*;` und bei Bedarf
`import java.awt.Color;`. Alle oeffentlichen Konstruktoren und Methoden der
vier Originalklassen sind vorhanden.

## Bedienelemente

- `Button(x,y,breite,hoehe,text,farbe)`: Browser-Button im SAS-Ausgabebereich.
  `clicked()` liefert einen gespeicherten Klick genau einmal zurueck.
  Sichtbarkeit, Aktivierung und Beschriftungsabfrage werden unterstuetzt.
- `Textfield(x,y,breite,hoehe,text,view)`: natives einzeiliges Eingabefeld mit
  Auswahl, Zwischenablage und normaler Tastaturbearbeitung. `setActivated`
  setzt/entfernt den Eingabefokus, wie im Original. `enterPressed()` verbraucht
  Enter einmal und entfernt dabei den Fokus. `clicked()` verbraucht einen
  gespeicherten Mausklick. Eingabetasten loesen keine IDE-Kurzbefehle aus.
- `Label(x,y,breite,hoehe,text,farbe[,rechts,monospaced])`: echter SAS-Sprite
  aus Hintergrund und Text. Bewegungen/Drehungen und Sichtbarkeit bleiben
  verfuegbar. Die Schrift passt sich an die Breite an; Textwechsel behalten
  die Transformation des Labels bei.

Buttons und Textfelder skalieren mit dem Ausgabecanvas. Beim Programmende
werden sie deaktiviert, beim Neustart entfernt. Label-Schriften und
Bedienelemente verwenden Browser-Schriften statt Swing/AWT; die Darstellung
ist deshalb nicht pixelgleich zum Original.

## StringFileTools

Dateinamen sind logische Namen, keine Pfade auf der Festplatte.

- Vorhandene Workspace-Dateien werden ueber den vorhandenen IDE-Dateimanager
  gelesen und geschrieben. Aenderungen daran koennen mit dem Workspace als
  JSON gespeichert werden.
- Neue Dateien werden im virtuellen Speicher dieser Interpreter-Sitzung
  angelegt. Sie bleiben bei Programmneustarts in derselben IDE erhalten,
  gehen beim Neuladen/Schliessen der IDE-Seite aber verloren. Sie werden
  aktuell nicht automatisch in den Workspace-Export aufgenommen.
- `writeInFile` ersetzt den Inhalt, `appendToFile` ergaenzt ihn. Beide haengen
  wie im Original einen Zeilenumbruch an.
- `loadFileInStringArray` liefert ein `String[]` ohne Zeilenumbruchzeichen.
  CRLF, CR und LF sowie Leerzeilen werden beruecksichtigt.
- `loadFileInString` verbindet die Zeilen mit LF, ohne letzten Zeilenumbruch.
  Eine leere Datei liefert einen leeren String statt des Originalfehlers.
- Fehlende Dateien liefern `false` bei `fileExists`, `null` bei den beiden
  Lademethoden und `-1` bei `countFileRows`.

Es gibt keinen automatischen Download, keinen Zugriff auf lokale Dateien
ausserhalb des Workspaces und keinen Schreibzugriff auf den Webserver.

## Test

Lokal: `http://localhost:3000/sas-book-demo/sasio-test.html`.
Der Beispielquelltext liegt in `sas-book-demo/examples/SASIOTest.java`.
Die Erweiterung ist noch nicht im Upload-Paket enthalten.
