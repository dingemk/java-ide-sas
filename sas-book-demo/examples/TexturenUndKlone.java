import sas.*;

View view = new View(600, 400, "SAS Texturen und Klone");
Rectangle r = new Rectangle(20, 20, 180, 100, "boote.jpg");
r.setDirection(180);

Shapes shape = r;
Shapes kopie = shape.clone();
kopie.move(220, 0);
println("Richtung: " + kopie.getDirection());

Sprite gruppe = new Sprite(r);
Sprite aussen = new Sprite(gruppe);
aussen.turn(10);
Sprite zweite = aussen.clone();
zweite.move(0, 180);

Circle kreis = new Circle(420, 180, 55);
kreis.loadTexture("boote.jpg");
println("Klone und Texturen OK");
