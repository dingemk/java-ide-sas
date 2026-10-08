# SAS-Kompatibilitaetspruefung

Stand: 05.10.2026. Referenz: `/Users/klaus/Desktop/sas.jar`, Klassendateien vom
17.01.2026. Beispiele: `/Users/klaus/Desktop/Programme.zip`.

## Pruefumfang und Ergebnis

- Alle oeffentlichen Deklarationen der zehn SAS-Kernklassen mit `javap -public`
  mit den registrierten Java-Deklarationen der Browserbibliothek verglichen.
- Fuer ausgewaehlte Verhaltensunterschiede den Original-Bytecode mit `javap -c -p`
  und den TypeScript-Quelltext verglichen.
- Alle 35 Java-Dateien, gruppiert in 13 Projekte, mit der SAS-Browserbibliothek
  kompiliert: **13 von 13 Projekten ohne Compilerfehler**.
- Diese Programme wurden in dieser Pruefung nicht interaktiv ausgefuehrt.
  Kompilierbarkeit beweist keine vollstaendige Laufzeitkompatibilitaet.
- Der bestehende `SASTest.java` prueft die meisten Aufrufe in einem `if(false)`;
  er deckt deren Ausfuehrung nicht ab.

Die Bibliothek ist noch **nicht vollstaendig originalkompatibel**.

## Umsetzungsschritt 1

Die folgenden Punkte der urspruenglichen Bestandsaufnahme sind inzwischen
lokal korrigiert; das Upload-Paket wurde bewusst nicht neu gebaut:

- Bildtexturen in Rectangle, Circle, Ellipse und Polygon, auch ueber den
  Dateinamen-Konstruktor und `loadTexture`. Bildpfade nutzen dieselbe
  Asset-Basis wie Picture. Fehlende Bilder lassen die vorhandene Farbe stehen.
- Klone liefern genau eine fertig initialisierte Kopie. SAS-Richtung,
  Sichtbarkeit, Textur und Transformation werden uebernommen.
- Verschachtelte Sprite-Klone haben eigene Kinder und behalten deren lokale
  Transformationen. Sprite-Methoden sind nun korrekt fuer Java registriert.
- 12 gezielte Tests fuer Texturen, Klone und Drehung sind erfolgreich.
- Ein Java-Browserlauf mit `Shapes.clone()`, verschachtelten Sprite-Klonen,
  Textur-Konstruktor und `loadTexture` endet ohne Laufzeitfehler mit
  `Richtung: 180` und `Klone und Texturen OK`.

Die untenstehende Bestandsaufnahme dokumentiert den Ausgangszustand.
Die weiteren Punkte, insbesondere reset, Sprite-Kindrichtungen, Transparenz,
Mausereignisse und die fehlenden Methoden, bleiben offen.

## Umsetzungsschritt 2: sasio

Die vier Klassen Button, Label, Textfield und StringFileTools sind lokal
implementiert. Alle oeffentlichen Original-Konstruktoren und Methoden sind
registriert. Button/Textfield sind native, skalierende Browser-Bedienelemente;
Label ist ein beweglicher SAS-Sprite. Dateifunktionen lesen/schreiben bestehende
Workspace-Dateien und nutzen fuer neue Dateien einen virtuellen Sitzungsspeicher.
Details und Browsergrenzen: `sasio-browser.md`.

Der Browser-Test prueft Eingabe, Enter, Button-Klicks, Label-Textwechsel,
Schreiben und Lesen. 96 Java-/SAS-Tests sind erfolgreich. Das Upload-Paket
wurde nicht erneuert.

## Fehlende oeffentliche Kernmethoden (Ausgangszustand)

Nachtrag Kollisionen 06.10.2026: Picture verwendet eine Alphaflaeche fuer
intersects und contains; transparente Raender, Loecher und Transformationen
sind getestet. contains fuer konkave Polygone und Sprites prueft jetzt die
vollstaendige Flaeche. Details und Browsergrenzen: `sas-collisions.md`.

Nachtrag 06.10.2026: Tools.getDirection, Tools.message und Tools.confirmDialog
sind implementiert. Dialoge warten auf die Antwort; confirmDialog liefert
0 (Ja), 1 (Nein), -1 (Schliessen/Escape). Text.setFontSansSerif,
setFontSerif und setFontMonospaced setzen Familie, Fettstil und Schriftgroesse.
Die Schreibweise setFontSanSerif ist als zusaetzlicher Alias vorhanden.
Testbeispiel: `sas-book-demo/examples/ToolsUndSchriftTest.java`.

Nachtrag UFO: `getShapeX/Y()` und `moveTo(x,y)` nutzen nun auch nach Drehungen
die linke obere Ecke des sichtbaren Begrenzungsrechtecks, nicht die mitgedrehte
urspruengliche Ecke. Dies gilt ebenfalls fuer Sprite. Das unveraenderte
UFO-Fluegelmuster `clone(); turn(180); moveTo(450,430)` ist als Regressionstest
abgedeckt. Browserbeispiel: `sas-book-demo/examples/UfoPositionTest.java`.

Nachtrag: `Tools.stringToDouble(String)` ist inzwischen lokal registriert und
implementiert, einschliesslich Dezimalpunkt, Exponenten und ungueltigen Eingaben.
Ungueltige Zahlen werfen derzeit `IllegalArgumentException`; der speziellere
Originaltyp `NumberFormatException` ist in der IDE noch nicht vorhanden.
Das Upload-Paket bleibt unveraendert.

20 Originalmethoden fehlen, zusaetzlich der Konstruktor der abstrakten
Basisklasse und deren oeffentliches Feld `scaleFaktor`.

| Klasse | Fehlende Originaldeklarationen |
| --- | --- |
| Shapes | `mouseReleased()`, `static double getScaleFactor()` |
| View | `char keyGetChar()` |
| Text | `setFontSansSerif(boolean,int)`, `setFontSerif(boolean,int)`, `setFontMonospaced(boolean,int)`, `setFontColor(Color)`, `getFontColor()`, `float getUl()`, `java.awt.Graphics getContext()` |
| Tools | `degreeToRadian(double)`, `stringToInt(String)`, `stringToDouble(String)`, `getDirection(double,double,double,double)`, `getHour()`, `getMinute()`, `getSecond()`, `message(String,String)`, `confirmDialog(String)`, `inputDialog(String)` |

Bei Rectangle, Circle, Ellipse, Polygon, Picture und Sprite sind die jeweils
eigenen oeffentlichen Konstruktoren und Methoden vorhanden. Die Luecken der
Basisklasse gelten auch fuer deren Unterklassen. Compiler-generierte
`clone()`-Bruecken wurden nicht als eigene fehlende Methoden gezaehlt.

`Shapes(double,double,double,double,Color)` fehlt als Konstruktor fuer eigene
Unterklassen. Geschuetzte SAS-Erweiterungspunkte fuer eigene Unterklassen sind
noch nicht vollstaendig geprueft.

## Vollstaendig fehlendes Zusatzpaket sasio

Die originale JAR enthaelt ausserdem:

- `sasio.Button`: Konstruktor, `clicked`, Sichtbarkeit, Aktivierung, Buttontext.
- `sasio.Label`: zwei Konstruktoren, `setLabelText`, `getLabelText`.
- `sasio.Textfield`: Konstruktor, Text, Loeschen, Aktivierung, Sichtbarkeit,
  `clicked`, `enterPressed`.
- `sasio.StringFileTools`: Existenzpruefung, Lesen als String/Stringarray,
  Zeilenzaehlung, Schreiben und Anhaengen.

Diese vier Klassen sind nicht registriert. Dateizugriff und Dialoge brauchen
eine Browserumsetzung. `sas.Scene` ist ebenfalls nicht abgebildet; es ist die
Swing-basierte Infrastruktur der Desktopversion. `Text.getContext()` erfordert
eine ausdrueckliche Entscheidung ueber die Unterstuetzung von AWT Graphics.

## Vorhanden, aber nicht originalgetreu

### Texturen: hoher praktischer Bedarf

`SASShapesClass._loadTexture()` ist ein leerer Platzhalter.
Bei Rectangle, Circle und Ellipse behandelt `_setFillColorOrTextureString`
Dateinamen wie Farben und ersetzt nicht erkennbare Werte durch eine graue
Flaeche. Polygon verwendet unmittelbar den Farbparser statt einen Texturlader.
`new Picture(...)` laedt Bilder bereits, ersetzt aber keine Texturen in Formen.

Betroffene Beispiele: `flappyBird/Roehre.java` (`rinde.png`),
`Staubsauger/Staubsaugerroboter2.java` (`teppich.jpg`),
`Ufo-Spiel/Asteroid.java` (`Krater.jpg`).

### clone und Sprite

`ShapeClass.copyFrom()` kopiert `directionRad`, aber nicht `sasDirection`.
Die Klone koennen deshalb anders weiterlaufen, als `getDirection()` beim
Original vorgibt. Mehrere konkrete Klonmethoden reichen den Callback bereits
an den Konstruktor weiter und rufen ihn anschliessend erneut auf; die
Fertigstellung wird dadurch vorzeitig beziehungsweise doppelt gemeldet.

`SASSpriteClass._mj$clone$Sprite$` waehlt zuerst `_mj$clone$Shapes$`.
Diese geerbte Methode ist ein leerer Platzhalter; bei konkreten Formen kann
dadurch die rekursive Klonkette ohne Callback stehenbleiben. Ein gezielter
Laufzeittest fuer verschachtelte Sprites ist erforderlich.

Unterobjektrichtungen bei `Sprite.turn()` werden im Original rekursiv
aktualisiert, in der Browserumsetzung nur die Richtung des Sprite selbst.

Betroffene Beispiele fuer konkrete Klone: `Ufo-Spiel/Ufo.java` und
`Windmuehlen/Windrad.java`.

### Transparenz

Im Original ist der Wert direkt der Alpha-Wert: `1.0f` ist deckend,
`0.0f` durchsichtig. Die Browserbibliothek rechnet stattdessen mit
0 bis 100 Prozent Transparenz. `setTransparency(0.5f)` setzt deshalb Alpha
auf 0.995 statt auf 0.5. Auch `getTransparency()` weicht entsprechend ab.
Unsere bisherige Verwendung von `setTransparency(35)` war nicht originalgetreu.

### Mausereignisse

`mousePressed()` liefert im Original ein einmalig verbrauchtes Druckereignis.
Die Browserumsetzung liefert den gehaltenen Zustand. Ein schnelles Druecken
und Loslassen zwischen zwei Abfragen kann verloren gehen. `mouseReleased()`
fehlt. `setHidden()` setzt im Original Mausflags zurueck, hier nicht.
Mauskoordinaten werden im Original beim Ereignis auf dem Objekt gespeichert;
hier werden sie auch bei beliebigen Mausbewegungen laufend aktualisiert.

Betroffene Beispiele: `BaelleKlicken/BaelleKlicken.java`,
`Objekte/Mausbeispiel.java`; Drag-and-drop gesondert testen.

### Picture ohne Groessenangabe

`Picture(x,y,dateiname)` uebernimmt im Original die natuerlichen Bildmasse.
Hier verwendet dieser Konstruktor immer 100 mal 100. Der Konstruktor mit
expliziter Breite und Hoehe ist davon nicht betroffen.

### reset und Geometrie

Das Original setzt auch Unterobjekttransformationen zurueck und behaelt die
Bewegungsrichtung. Die Browserumsetzung setzt `sasDirection` auf 90 und
setzt Unterobjekte nicht rekursiv zurueck. Text speichert seine Anfangsposition
als Containertranslation; Identitaetsreset kann ihn deshalb zum Ursprung versetzen.
Betroffen: `Windmuehlen/test.java`.

`contains(Shapes)` prueft derzeit nur Polygonpunkte statt die gesamte Flaeche.
Bei konkaven Formen und Sprites ist das kein vollstaendiger Flaechentest.
`intersects()` verwendet die vorhandene IDE-Kollisionslogik; deren Gleichheit
mit den AWT-Flaechentests ist nicht generell nachgewiesen.

### Text und Zeitformat

Original-Text beginnt mit Serif, fett, Groesse 24; Browser-Text mit Arial,
normal, Groesse 20. Fontfarbe und Formfarbe sind im Original getrennt.
`getElapsedTime(long)` kuerzt im Original auf Hundertstelsekunden;
die Browserfassung gibt ungekappte Millisekundenbruchteile zurueck.
`getElapsedTimeString(long)` unterscheidet sich bei Rundung und lokaler
Dezimaldarstellung.

## Empfohlene Reihenfolge

1. Texturen in normalen Formen und Dateinamen-Konstruktoren.
2. Klonen, Sprite-Unterobjekte und reset.
3. Natuerliche Picture-Groesse, Mausereignisse und Transparenz.
4. Textschriftarten und Fontfarben, keyGetChar und Tools-Hilfsfunktionen.
5. Dialoge und das Zusatzpaket sasio; AWT-Kontext gesondert planen.

## Erneut pruefen

```sh
SAS_EXAMPLES_ZIP=/pfad/Programme.zip npx vitest --config vitest.config.ts run scripts/audit-sas-examples.test.ts
```

Der strukturierte Compilerbericht liegt in
`documentation/sas-example-compile-audit.json`. Der Auditlauf schreibt diesen
Bericht neu; er startet keine Beispielprogramme.
