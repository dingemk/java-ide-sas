import sasio.*;

String[] woerter = StringFileTools.loadFileInStringArray("woerter.txt");
if (woerter == null) {
    println("Bitte zuerst woerter.txt in den Workspace laden.");
} else {
    for (String wort : woerter) println(wort);
}
