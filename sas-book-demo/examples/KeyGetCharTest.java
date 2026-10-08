import sas.*;
import java.awt.Color;

View view = new View(400, 200, "Tastaturtest");
view.setBackgroundColor(Color.WHITE);
Text ausgabe = new Text(20, 40, "Bitte ein Zeichen eingeben.", Color.BLACK);
char zeichen = view.keyGetChar();
ausgabe.setText("Eingabe: " + zeichen);
println("Eingabe: " + zeichen);
