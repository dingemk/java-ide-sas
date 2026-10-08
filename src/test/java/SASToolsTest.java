/**::
 * Test SAS string conversion
 * {"libraries": ["sas"]}
 */
import sas.*;
assertEquals(3.14, Tools.stringToDouble("3.14"), "decimal");
assertEquals(-2.5, Tools.stringToDouble(" -2.5 "), "signed decimal");
assertEquals(1000.0, Tools.stringToDouble("1e3"), "exponent");
assertEquals(6.0, Tools.stringToDouble("0x1.8p2"), "hexadecimal");
boolean rejected = false;
try {
    Tools.stringToDouble("3,14");
} catch (IllegalArgumentException e) {
    rejected = true;
}
assertEquals(true, rejected, "invalid number");
assertEquals(0.0, Tools.getDirection(10, 20, 10, 10), "up");
assertEquals(90.0, Tools.getDirection(10, 20, 20, 20), "right");
assertEquals(180.0, Tools.getDirection(10, 20, 10, 30), "down");
assertEquals(270.0, Tools.getDirection(10, 20, 0, 20), "left");
if (false) {
    View view = new View();
    char key = view.keyGetChar();
    view.keyBufferDelete();
    Tools.message("Nachricht", "Titel");
    int answer = Tools.confirmDialog("Weiter?");
    String input = Tools.inputDialog("Name?");
    Text text = new Text(10, 10, "Hallo");
    text.setFontSansSerif(true, 24);
    text.setFontSanSerif(false, 18);
    text.setFontSerif(false, 18);
    text.setFontMonospaced(true, 16);
}
