# SAS-Kollisionen im Browser

## Picture

Beim Laden wird aus dem Alphakanal der dargestellten Bildgroesse eine
Kollisionsflaeche aufgebaut. Pixel mit Alpha 0 sind frei; alle anderen Pixel,
auch teiltransparente, zaehlen zur Flaeche. Transparente Loecher und getrennte
Bildteile bleiben erhalten. Das betrifft intersects, contains(Shapes),
contains(x,y) und die darauf aufbauenden SAS-Mausereignisse.

Die Flaeche wird mit dem Bild bewegt, gedreht, skaliert und gespiegelt, auch
innerhalb von Sprites. Klone nutzen dieselbe bereits ausgewertete Alphamaske,
aber ihre eigene Position und Transformation. Picture(x,y,dateiname) verwendet
jetzt die natuerliche Bildgroesse, entsprechend der Originalbibliothek.

Wenn der Browser das Auslesen von Bildpixeln verbietet (z.B. fremder Server
ohne passende CORS-Freigabe), wird mit einer Konsolenwarnung auf die bisherige
rechteckige Flaeche zurueckgefallen. Workspace-Uploads und Bilder vom eigenen
Server sind davon normalerweise nicht betroffen. Die Warnung bedeutet, dass
die Alpha-Kollision fuer dieses Bild nicht aktiv ist.

## Polygon und andere SAS-Formen

Polygone verwenden die geschlossene Kontur ihrer Eckpunkte, nicht ihr
umgebendes Rechteck. Konkave Einbuchtungen bleiben frei. intersects prueft
eine Flaechenueberlappung; blosses Beruehren einer Kante zaehlt wie beim
Area-Test der Originalbibliothek nicht als Treffer.

contains(Shapes) prueft jetzt die gesamte andere Flaeche. Es reicht nicht
mehr, dass lediglich alle Eckpunkte innerhalb liegen. Sprite-Flaechen werden
aus den sichtbaren Kindern zusammengesetzt. Versteckte und zerstoerte Formen
werden nicht als Treffer gewertet.

Bildtexturen auf Polygonen, Rechtecken usw. veraendern weiterhin nicht deren
geometrische Kontur; die Alphamaske betrifft Picture. Kreise und Ellipsen
verwenden weiterhin die vorhandenen polygonalen Annaeherungen der IDE.

## Testen

`sas-book-demo/alpha-kollision-test.html` zeigt ein PNG mit transparentem Rand
und Loch sowie ein konkaves Polygon. Alle ausgegebenen Werte muessen den
danebenstehenden Erwartungen entsprechen. Unit-Tests stehen in
`src/test/SASAlphaCollision.test.ts`.

Fuer Flaechenoperationen wird polygon-clipping verwendet. Dessen Lizenz
wird vom Paketierungsprozess in THIRD-PARTY-LICENSES.txt aufgenommen.
