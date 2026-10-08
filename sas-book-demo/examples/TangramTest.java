import sas.*;
import java.awt.Color;

class Tangram {
    View view = new View(350, 500, "Tangram");
    Polygon[] grundfigur = new Polygon[7];

    Tangram() {
        view.setBackgroundColor(Color.BLACK);
        new Rectangle(200, 100, 120, 120, Color.WHITE);
        Text text = new Text(130, 450, "Mit Mausklick drehen.", Color.RED);
        text.setFontSansSerif(true, 12);
        Text text2 = new Text(130, 470, "Mit 'u' Parallelogramm umdrehen", Color.RED);
        text2.setFontSansSerif(true, 12);
        grundfigur[0] = dreieck(80, 120, 60, -60, Color.MAGENTA);
        grundfigur[1] = dreieck(160, 120, 60, -60, Color.RED);
        grundfigur[2] = dreieck(240, 60, 60, -60, Color.GREEN);
        grundfigur[3] = dreieck(290, 60, 30, -30, Color.BLUE);
        grundfigur[4] = dreieck(340, 60, 30, -30, new Color(0, 191, 255));
        grundfigur[5] = new Polygon(10, 390, Color.GRAY);
        grundfigur[5].add(30, -30);
        grundfigur[5].add(60, 0);
        grundfigur[5].add(30, 30);
        grundfigur[6] = new Polygon(10, 470, Color.YELLOW);
        grundfigur[6].add(60, 0);
        grundfigur[6].add(90, -30);
        grundfigur[6].add(30, -30);
    }

    Polygon dreieck(int y, int width, int x, int dy, Color color) {
        Polygon pol = new Polygon(10, y, color);
        pol.add(width, 0);
        pol.add(x, dy);
        return pol;
    }

    int getDraggedIndex() {
        for (int i = 0; i < 7; i++) {
            if (grundfigur[i].mouseDragged()) return i;
        }
        return -1;
    }

    int getClickedIndex() {
        for (int i = 0; i < 7; i++) {
            if (grundfigur[i].mouseClicked()) return i;
        }
        return -1;
    }

    void fuehreAus() {
        while (!view.keyPressed('q')) {
            if (view.keyPressed('u')) {
                grundfigur[6].flipHorizontal();
                view.keyBufferDelete();
            }
            int index = getClickedIndex();
            if (index != -1) grundfigur[index].turn(45);
            int index2 = getDraggedIndex();
            if (index2 != -1) {
                int altX = grundfigur[index2].getMouseX();
                int altY = grundfigur[index2].getMouseY();
                int neuX = altX;
                int neuY = altY;
                while (grundfigur[index2].mouseDragged()) {
                    grundfigur[index2].move(neuX - altX, neuY - altY);
                    altX = neuX;
                    altY = neuY;
                    neuX = grundfigur[index2].getMouseX();
                    neuY = grundfigur[index2].getMouseY();
                }
            }
            view.wait(10);
        }
    }
}

Tangram tg = new Tangram();
tg.fuehreAus();
