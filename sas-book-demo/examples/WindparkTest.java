import sas.*;
import java.awt.Color;

class Windrad {
    private Sprite windrad;
    private Sprite rotor;
    private double drehPunktX;
    private double drehPunktY;

    Windrad(double xPos, double yPos, double breite, double hoehe, Color farbe) {
        drehPunktX = xPos + breite / 2;
        drehPunktY = yPos - hoehe;
        Polygon mast = new Polygon(xPos, yPos, farbe);
        mast.add(breite, 0);
        mast.add(0.9 * breite, -hoehe);
        mast.add(0.1 * breite, -hoehe);
        windrad = new Sprite(mast);
        double radius = breite * 2 / 5;
        windrad.add(new Circle(drehPunktX - radius, drehPunktY - radius, radius, Color.white));

        double laenge = hoehe / 2;
        Ellipse ellipse = new Ellipse(drehPunktX, drehPunktY - laenge / 20, laenge, laenge / 10, Color.white);
        rotor = new Sprite(ellipse);
        Ellipse ellipse1 = ellipse.clone();
        ellipse1.turn(drehPunktX, drehPunktY, 120);
        rotor.add(ellipse1);
        Ellipse ellipse2 = ellipse.clone();
        ellipse.turn(drehPunktX, drehPunktY, 240);
        rotor.add(ellipse2);
        windrad.add(rotor);
    }

    void dreheRotor(double geschwindigkeit) {
        rotor.turn(drehPunktX, drehPunktY, geschwindigkeit);
    }
}

View view = new View(800, 600, "Windpark-Test");
view.setBackgroundColor(new Color(80, 170, 110));
Windrad w1 = new Windrad(160, 550, 20, 300, Color.red);
Windrad w2 = new Windrad(410, 470, 18, 200, Color.blue);
Windrad w3 = new Windrad(660, 390, 10, 100, Color.red);
for (int i = 0; i < 360; i++) {
    w1.dreheRotor(1);
    w2.dreheRotor(2);
    w3.dreheRotor(3);
    view.wait(20);
}
println("Windpark-Test ohne Laufzeitfehler beendet");
