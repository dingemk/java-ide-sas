/**::
 * Test explicit SAS Tools import
 * {"libraries": ["sas"]}
 */
import sas.Tools;
assertEquals(3.5, Tools.stringToDouble("3.5"), "explicit Tools import");
assertEquals(90.0, Tools.getDirection(0, 0, 10, 0), "explicit Tools call");
int stunde = Tools.getHour();
int minute = Tools.getMinute();
int sekunde = Tools.getSecond();
assertTrue(stunde >= 0 && stunde <= 23, "hour range");
assertTrue(minute >= 0 && minute <= 59, "minute range");
assertTrue(sekunde >= 0 && sekunde <= 59, "second range");
if (false) {
    String input = Tools.inputDialog("Name?");
}
