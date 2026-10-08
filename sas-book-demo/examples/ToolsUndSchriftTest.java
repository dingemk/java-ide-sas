import sas.*;
import java.awt.Color;

View view = new View(600, 250, "Tools und Schrift");
view.setBackgroundColor(Color.white);
Text titel = new Text(20, 20, "SAS Sans Serif", Color.black);
titel.setFontSansSerif(true, 28);
Text serif = new Text(20, 80, "SAS Serif", Color.black);
serif.setFontSerif(false, 24);
Text mono = new Text(20, 140, "SAS Monospaced", Color.black);
mono.setFontMonospaced(true, 22);
println("Richtung nach rechts: " + Tools.getDirection(0, 0, 100, 0));
Tools.message("Die drei Schriftarten sind gesetzt.", "Schrifttest");
int antwort = Tools.confirmDialog("Soll es weitergehen?");
println("Antwort: " + antwort);
