import nrw.*;
import sas.Tools;

List<String> liste = new List<>();
liste.append("NRW");
liste.toFirst();
println("List: " + liste.getContent());

Stack<String> stapel = new Stack<>();
stapel.push("oben");
println("Stack: " + stapel.top());

Queue<String> warteschlange = new Queue<>();
warteschlange.enqueue("vorne");
println("Queue: " + warteschlange.front());

BinaryTree<String> baum = new BinaryTree<>("Wurzel");
baum.setLeftTree(new BinaryTree<>("Links"));
baum.setRightTree(new BinaryTree<>("Rechts"));
println("BinaryTree: " + baum.getLeftTree().getContent());

class Zahl implements ComparableContent<Zahl> {
    int wert;
    Zahl(int wert) { this.wert = wert; }
    public boolean isGreater(Zahl andere) { return wert > andere.wert; }
    public boolean isLess(Zahl andere) { return wert < andere.wert; }
    public boolean isEqual(Zahl andere) { return wert == andere.wert; }
}
BinarySearchTree<Zahl> suchbaum = new BinarySearchTree<>();
suchbaum.insert(new Zahl(2));
suchbaum.insert(new Zahl(1));
println("BinarySearchTree: " + suchbaum.search(new Zahl(1)).wert);

Graph graph = new Graph();
Vertex a = new Vertex("A");
Vertex b = new Vertex("B");
graph.addVertex(a);
graph.addVertex(b);
graph.addEdge(new Edge(a, b, 3));
println("Graph: " + graph.getEdge(a, b).getWeight());

DatabaseConnector datenbank = null;
QueryResult ergebnis = null;
println("SAS Tools: " + Tools.stringToDouble("3.5"));
