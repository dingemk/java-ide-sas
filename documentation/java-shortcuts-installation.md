# Java-Shortcuts: C, N und I

## JSON-Workspace oeffnen

```text
[@[java-ide::breakout.json]@]
```

Zeigt den Dateinamen und darunter einen Button. Die JSON-Datei liegt im
gleichen Upload-Ordner wie die anderen Dateien dieser Buchseite; der Pfad
wird mit `get_SeitePathPublic()` ermittelt. Sie muss ein exportierter
IDE-Workspace mit `modules` sein, nicht beliebiges JSON. Wie beim bisherigen
URL-Import wird bei einem Export mit mehreren Workspaces der erste geladen.
Der bisherige IDE-Workspace wird ersetzt. Andere Server muessen den Zugriff
per CORS erlauben, wenn Buchdatei und IDE unterschiedliche Origins haben.

Die komplette Engine-Version ist `txt_engine.11.13.MIT-JAVA-IDE.php`.
Sie auf dem Server als `txt_engine.11.13.php` verwenden. Zusaetzlich die
aktuelle `upload/java-ide/sas-book-demo/runner.html` hochladen.
Bei manueller Integration die Methode `java_ide()` und den aktualisierten
`java_shortcut_script()` uebernehmen und im Dispatch ergaenzen:

```php
case 'java-ide':
    $this->java_ide($s, $p);
    break;
```

## Quelltext-Buttons

In `txt_engine.11.13.php` die vorhandenen Methoden `java_source()` und
`java_text()` durch die gleichnamigen Methoden aus `java-shortcut-functions.php`
ersetzen. Die drei Methoden `java_shortcut_parameters()`,
`java_shortcut_render()` und `java_shortcut_script()` ebenfalls innerhalb
derselben Engine-Klasse einfuegen. Den PHP-Oeffnungstag aus der Lieferdatei
nicht mit einfuegen. Die bestehenden `case`-Eintraege bleiben unveraendert.

```text
[@[java-text::System.out.println("Hallo");::C]@]
[@[java-text::class Beispiel {}::CN]@]
[@[java-text::class ZweiteKlasse {}::CI]@]
[@[java-datei::Beispiel.java::CN]@]
[@[java-datei::Beispiel.java::von=1,bis=0,Zeilennummern=true::CI]@]
```

- `C`: dargestellten Quelltext kopieren.
- `N`: IDE oeffnen und Workspace durch diesen Quelltext ersetzen.
- `I`: Datei im vorhandenen IDE-Workspace dieser Buchseite hinzufuegen.
  Beim ersten Oeffnen wird ein Workspace mit dieser Datei angelegt.
- `CN`, `CI` und `CNI`: entsprechende Buttons kombinieren.
- Ohne neue Flags: nur formatierter Quelltext wie bisher.

Die IDE wird in einem benannten Browser-Tab geoeffnet. Weitere Klicks verwenden
denselben Tab und fordern den Fokus an; die Browser-Einstellungen entscheiden,
ob er als Tab oder Fenster erscheint und in den Vordergrund wechseln darf.
Ein manuell geoeffneter IDE-Tab kann nicht automatisch gefunden werden.
Bei blockierten Pop-ups muss die Buchseite freigegeben werden.
Vor einem `N`-Klick notwendige Aenderungen sichern: `N` ersetzt den Workspace.

Bei `I` laesst sich der Dateiname vor dem Hinzufuegen bearbeiten; vorhandene
Dateien werden nicht ueberschrieben. Bei `java-datei` stammt der Name aus dem
Dateinamen, bei `java-text` wird er aus einer Typdeklaration vorgeschlagen
(sonst `Program.java`). Bei mehreren Deklarationen oder Typnamen in Kommentaren
den vorgeschlagenen Namen pruefen. Fuer eine oeffentliche Klasse muss der Name
passen. Ein gewaehlter Zeilenausschnitt wird auch als Ausschnitt uebertragen;
zum Ausfuehren einer vollstaendigen Datei `von=1,bis=0` verwenden.

IDE-Adresse im JavaScript: `https://net-schulbuch.de/java-ide/sas-book-demo/runner.html`.
Die hochgeladene IDE muss das bestehende `sas-book:add-file`-Protokoll
unterstuetzen und Nachrichten von `window.opener` akzeptieren. Dazu die
aktualisierte `sas-book-demo/runner.html` ebenfalls auf den IDE-Server hochladen.
Die Buchseite muss Inline-JavaScript erlauben; bei einer CSP mit
Nonce-Vorgabe die Script-Tags entsprechend in das bestehende System integrieren.
