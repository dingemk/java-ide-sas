import sas.*;
import java.awt.Color;

View view = new View(400, 600, "Ball zuruecksetzen");
view.setBackgroundColor(Color.WHITE);
Circle start = new Circle(60, 500, 30, Color.YELLOW);
Circle ball = new Circle(60, 500, 30, Color.MAGENTA);
ball.turnTo(60);
ball.move(200);
println("n: Ball zur Ausgangsposition zuruecksetzen");
char taste = view.keyGetChar();
if (taste == 'n') {
    ball.moveTo(60, 500);
    println("Ball x/y: " + ball.getShapeX() + "/" + ball.getShapeY());
    println("Mittelpunkt: " + ball.getCenterX() + "/" + ball.getCenterY());
}
