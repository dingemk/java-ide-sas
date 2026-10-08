/**::
 * Test SAS library core types
 * {"libraries": ["sas"]}
 */

import sas.*;
import java.awt.Color;

View view = null;
Shapes shape = null;
Rectangle rectangle = null;
Circle circle = null;
Ellipse ellipse = null;
Polygon polygon = null;
Text text = null;
Sprite sprite = null;
Picture picture = null;

if(false){
    view = new View(600, 400, "SAS");
    view.setSize(320, 240);
    view.setName("SAS");
    view.setBackgroundColor("white");
    view.setBackgroundColor(new Color(255, 255, 255));
    view.wait(10);
    view.keyBufferDelete();
    view.keyPressed();
    view.keyPressed('a');
    view.keyLeftPressed();
    view.keyRightPressed();
    view.keyUpPressed();
    view.keyDownPressed();
    view.keyEnterPressed();
    view.keyBackspacePressed();
    view.getMouseX();
    view.getMouseY();

    rectangle = new Rectangle(10, 20, 30, 40, "red");
    circle = new Circle(50, 60, 20, new Color(0, 0, 255));
    ellipse = new Ellipse(100, 100, 80, 40, Color.green);
    ellipse = new Ellipse(500, 80, 200, 150, "wasser.png");
    rectangle = new Rectangle(0, 0, 100, 100, "teppich.jpg");
    polygon = new Polygon(120, 300, Color.magenta);
    text = new Text(130, 10, "Grundobjekte in Shapes and Sprites", Color.red);
    picture = new Picture(270, 300, 300, 200, "chagall.png");
    picture = new Picture(0, 0, "landschaft.jpg");
    sprite = new Sprite();
    sprite.add(rectangle);
    sprite.add(circle);
    shape = rectangle;

    shape.getColor();
    shape.setColor("green");
    shape.setColor(Color.RED);
    shape.setColor(new Color(255, 0, 0));
    shape.setHidden(false);
    shape.setTransparency(25);
    shape.setDirection(90);
    shape.move(10);
    shape.move(5, 6);
    shape.moveTo(1, 2);
    shape.turn(45);
    shape.turn(10, 10, 45);
    shape.turnTo(90);
    shape.turnTo(100, 100);
    shape.scale(2, 3);
    shape.scaleTo(100, 120);
    shape.flipHorizontal();
    shape.flipVertical();
    shape.loadTexture("texture.png");
    shape.contains(circle);
    shape.intersects(circle);
    shape.contains(15, 25);
    shape.mousePressed();
    shape.mouseDragged();
    shape.mouseClicked();
    shape.getMouseX();
    shape.getMouseY();
    shape.reset();
    view.remove(circle);

    rectangle.clone();
    circle.clone();
    ellipse.clone();
    picture.clone();
    polygon.add(0, 0);
    polygon.add(50, 0);
    polygon.add(25, 40);
    polygon.clone();
    text.setText("Zeit: " + Tools.getStopTime() + " Sekunden");
    text.setFontsize(24);
    text.clone();
    sprite.move(10);
    sprite.turnTo(300, 200);
    sprite.clone();
    long start = Tools.getStartTime();
    Tools.randomNumber(1, 10);
    Tools.getStopTime();
    Tools.getStopTime(start);
    Tools.getStopTimeString();
    Tools.getStopTimeString(start);
    Tools.getElapsedTime(start);
    Tools.getElapsedTimeString();
    Tools.getElapsedTimeString(start);
}

assertEquals(null, view, "SAS core types should be available.");
