import sas.Tools;

String name = Tools.inputDialog("Wie heisst du?");
if (name == null) {
    println("Eingabe abgebrochen.");
} else {
    println("Hallo " + name + "!");
}
