/**::
 * Test SAS and all NRW library types together
 * {"libraries": ["sas", "nrw"]}
 */
import nrw.*;
import sas.Tools;
List<String> liste = new List<>();
liste.append("NRW");
liste.toFirst();
assertEquals("NRW", liste.getContent(), "NRW List");
Stack<String> stapel = new Stack<>();
stapel.push("oben");
assertEquals("oben", stapel.top(), "NRW Stack");
Queue<String> schlange = new Queue<>();
schlange.enqueue("vorne");
assertEquals("vorne", schlange.front(), "NRW Queue");
BinaryTree<String> baum = new BinaryTree<>("Wurzel");
baum.setLeftTree(new BinaryTree<>("Links"));
assertEquals("Links", baum.getLeftTree().getContent(), "NRW BinaryTree");
class Zahl implements ComparableContent<Zahl> {
    int wert;
    Zahl(int wert) { this.wert = wert; }
    public boolean isGreater(Zahl andere) { return wert > andere.wert; }
    public boolean isLess(Zahl andere) { return wert < andere.wert; }
    public boolean isEqual(Zahl andere) { return wert == andere.wert; }
}
BinarySearchTree<Zahl> suche = new BinarySearchTree<>();
suche.insert(new Zahl(2));
suche.insert(new Zahl(1));
assertEquals(1, suche.search(new Zahl(1)).wert, "NRW BinarySearchTree");
Graph graph = new Graph();
Vertex a = new Vertex("A");
Vertex b = new Vertex("B");
graph.addVertex(a);
graph.addVertex(b);
graph.addEdge(new Edge(a, b, 3));
assertEquals(3.0, graph.getEdge(a, b).getWeight(), "NRW graph");
DatabaseConnector datenbank = null;
QueryResult ergebnis = null;
assertEquals(3.5, Tools.stringToDouble("3.5"), "SAS remains available");
