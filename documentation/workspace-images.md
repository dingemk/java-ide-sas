# Bilder im eingebetteten Workspace

Neben Workspace laden/speichern gibt es einen Icon-Button mit dem Tooltip
"Bild in Workspace laden (PNG/JPG)". Er erlaubt auch mehrere Bilder gleichzeitig.
Unterstuetzt werden PNG, JPG und JPEG bis 10 MB pro Bild. Dateiformat und
Lesbarkeit werden vor dem Einfuegen geprueft. Bei gleichen Dateinamen wird vor
dem Ersetzen nachgefragt.

Bilder erscheinen in der Dateiliste; Anklicken zeigt eine Vorschau statt
Base64-Text. Die Dateien werden nicht als Java kompiliert. Beispiel:

```java
import sas.*;
View view = new View(600, 400);
Picture bild = new Picture(0, 0, 600, 400, "boote.jpg");
```

Picture und SAS-Bildtexturen suchen zuerst im Workspace. Fehlt dort ein Bild,
bleibt die bisherige Aufloesung ueber die konfigurierte Asset-Basis erhalten.

Die Bilddaten werden als Data-URL im Textfeld einer Workspace-Datei gespeichert.
Damit nimmt der bestehende JSON-Workspace-Export sie ohne externe Bilddateien
mit. Die eingebettete Workspace-Importfunktion stellt diese Dateien wieder her.
Eine bloße Programm-URL mit `#code=...` enthaelt dagegen keine Bilder.
Bei eingebetteten IDEs ohne dauerhaften Browser-Speicher sollte vor dem
Schliessen der Workspace als JSON gespeichert werden.

Der Upload bleibt lokal im Browser: Bilder werden nicht auf einen Server
hochgeladen. Das Web-Upload-Paket wurde noch nicht neu gebaut.
