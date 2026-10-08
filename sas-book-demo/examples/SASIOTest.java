import sas.*;
import sasio.*;
import java.awt.Color;

View view = new View(640, 300, "sasio-Test");
view.setBackgroundColor(new Color(240, 244, 247));
Label titel = new Label(20, 20, 600, 32, "sasio: Eingabe und Ausgabe", Color.white);
Textfield eingabe = new Textfield(20, 70, 340, 36, "Hallo SAS", view);
Button uebernehmen = new Button(380, 70, 240, 36, "Uebernehmen", Color.white);
Label ausgabe = new Label(20, 125, 600, 36, "Hallo SAS", Color.white, true, true);
Button speichern = new Button(20, 185, 180, 36, "Speichern", Color.white);
Button laden = new Button(220, 185, 180, 36, "Laden", Color.white);
Button ende = new Button(420, 185, 200, 36, "Beenden", Color.white);
Label status = new Label(20, 240, 600, 28, "Bereit", Color.white);
eingabe.setActivated(true);

while (!ende.clicked()) {
    if (uebernehmen.clicked() || eingabe.enterPressed()) {
        ausgabe.setLabelText(eingabe.getText());
        status.setLabelText("Text uebernommen");
    }
    if (speichern.clicked()) {
        StringFileTools.writeInFile("eingabe.txt", eingabe.getText());
        status.setLabelText("Gespeichert: " + StringFileTools.countFileRows("eingabe.txt") + " Zeile(n)");
    }
    if (laden.clicked()) {
        if (StringFileTools.fileExists("eingabe.txt")) {
            ausgabe.setLabelText(StringFileTools.loadFileInString("eingabe.txt"));
            String[] zeilen = StringFileTools.loadFileInStringArray("eingabe.txt");
            println("Geladen: " + zeilen[0]);
            status.setLabelText("Datei geladen");
        } else {
            status.setLabelText("Noch keine Datei gespeichert");
        }
    }
    view.wait(20);
}
println("sasio-Test beendet");
