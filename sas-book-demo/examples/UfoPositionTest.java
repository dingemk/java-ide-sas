import sas.*;
import java.awt.Color;

View view = new View(220, 120, "UFO-Positionstest");
Ellipse cockpit = new Ellipse(380, 410, 60, 40, Color.blue);
Rectangle rumpf = new Rectangle(370, 430, 80, 30, Color.red);
Polygon fluegel1 = new Polygon(370, 430, Color.green);
fluegel1.add(-30, 15);
fluegel1.add(0, 30);
Polygon fluegel2 = fluegel1.clone();
fluegel2.turn(180);
fluegel2.moveTo(450, 430);
println("Rechter Fluegel: " + fluegel2.getShapeX() + "/" + fluegel2.getShapeY());
Sprite ufo = new Sprite(rumpf);
ufo.add(cockpit);
ufo.add(fluegel1);
ufo.add(fluegel2);
ufo.move(-300, -380);
