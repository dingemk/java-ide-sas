import sas.*;
import java.awt.Color;

View view = new View(640, 320, "Alpha- und Polygonkollision");
view.setBackgroundColor(Color.white);
Text titel = new Text(20, 15, "PNG mit Loch und konkaves Polygon", Color.black);
titel.setFontSansSerif(true, 22);
Picture bild = new Picture(20, 70, 180, 180, "alpha-kollision-test.png");
Rectangle probe = new Rectangle(28, 78, 8, 8, Color.blue);
println("Bild Hintergrund: " + bild.intersects(probe) + " (erwartet false)");
probe.moveTo(106, 156);
println("Bild Loch: " + bild.intersects(probe) + " (erwartet false)");
probe.moveTo(55, 105);
println("Bild sichtbarer Bereich: " + bild.intersects(probe) + " (erwartet true)");

Polygon winkel = new Polygon(300, 70, new Color(0, 180, 90));
winkel.add(180, 0);
winkel.add(180, 40);
winkel.add(40, 40);
winkel.add(40, 180);
winkel.add(0, 180);
Rectangle aussparung = new Rectangle(380, 150, 20, 20, Color.blue);
println("Polygon Aussparung: " + winkel.intersects(aussparung) + " (erwartet false)");
Polygon bruecke = new Polygon(320, 90, Color.red);
bruecke.add(140, 0);
bruecke.add(0, 140);
println("Polygon enthaelt Bruecke: " + winkel.contains(bruecke) + " (erwartet false)");
bruecke.setHidden(true);
