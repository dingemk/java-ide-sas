import { describe, expect, it } from 'vitest';
import { SASShapesClass } from '../compiler/java/runtime/graphics/sas/SASShapesClass';
import { ColorClass } from '../compiler/java/runtime/graphics/ColorClass';

function shape() {
    const result = new SASShapesClass();
    result.render = () => {};
    return result;
}

describe('SAS color identity', () => {
    it('preserves the original color object and supports the Mastermind cycle', () => {
        const circle = shape();
        const colors = [
            new ColorClass()._constructorColorClass(255, 127, 36),
            new ColorClass()._constructorColorClass(0, 255, 0),
            new ColorClass()._constructorColorClass(255, 0, 0),
            new ColorClass()._constructorColorClass(0, 0, 255)
        ];
        circle._setFillColorColor(colors[0]);
        for (let i = 0; i < 12; i++) {
            const index = colors.findIndex(color => color === circle._getFillColor());
            expect(index).toBe(i % colors.length);
            circle._setFillColorColor(colors[(index + 1) % colors.length]);
        }
    });

    it('lets two circles compare the same supplied color by identity', () => {
        const color = new ColorClass()._constructorColorClass(99, 184, 255);
        const guess = shape();
        const code = shape();
        guess._setFillColorColor(color);
        code._setFillColorColor(color);
        expect(guess._getFillColor()).toBe(code._getFillColor());
    });

    it('invalidates the color reference for numeric and string setters', () => {
        const circle = shape();
        const color = new ColorClass()._constructorColorClass(255, 0, 0);
        circle._setFillColorColor(color);
        circle._setFillColorInt(0);
        const black = circle._getFillColor();
        expect([black.red, black.green, black.blue]).toEqual([0, 0, 0]);
        expect(circle._getFillColor()).toBe(black);
        circle._setFillColorString('blue');
        expect(circle._getFillColor()._toInt()).toBe(0x0000ff);
        circle._setFillColorColor(null);
        expect(circle._getFillColor()).toBeNull();
    });
});
