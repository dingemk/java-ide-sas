/**::
 * Test sasio API and virtual files
 * {"libraries": ["sas"]}
 */
import sas.*;
import sasio.*;
import java.awt.Color;

if (false) {
    View view = new View();
    Button button = new Button(10, 10, 100, 30, "OK", Color.white);
    button.clicked();
    button.setHidden(true);
    button.getHidden();
    button.setActivated(false);
    button.getActivated();
    button.getButtonText();
    Label label = new Label(10, 60, 200, 30, "Text", Color.white);
    Label right = new Label(10, 100, 200, 30, "Text", Color.white, true, true);
    label.setLabelText("Neu");
    label.getLabelText();
    label.move(20, 0);
    label.turn(10);
    label.clone();
    Textfield field = new Textfield(10, 140, 200, 30, "Hallo", view);
    field.getText();
    field.deleteText();
    field.setActivated(true);
    field.getActivated();
    field.clicked();
    field.enterPressed();
    field.setHidden(true);
}
new StringFileTools();
StringFileTools.writeInFile("sasio-test.txt", "eins");
StringFileTools.appendToFile("sasio-test.txt", "zwei");
assertEquals(true, StringFileTools.fileExists("sasio-test.txt"), "file exists");
assertEquals(2, StringFileTools.countFileRows("sasio-test.txt"), "line count");
assertEquals("eins\nzwei", StringFileTools.loadFileInString("sasio-test.txt"), "file text");
String[] rows = StringFileTools.loadFileInStringArray("sasio-test.txt");
assertEquals(2, rows.length, "array length");
assertEquals("zwei", rows[1], "array line");
