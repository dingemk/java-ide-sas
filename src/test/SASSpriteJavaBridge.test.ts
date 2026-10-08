import { expect, it, vi } from 'vitest';
import * as PIXI from 'pixi.js';
import '../compiler/common/interpreter/Interpreter';
import { JavaCompiler } from '../compiler/java/JavaCompiler';
import { JavaLibraryManager } from '../compiler/java/runtime/JavaLibraryManager';
import { CompilerFile } from '../compiler/common/module/CompilerFile';
import { Thread } from '../compiler/common/interpreter/Thread';
import { SASSpriteClass } from '../compiler/java/runtime/graphics/sas/SASSpriteClass';
import { SASRectangleClass } from '../compiler/java/runtime/graphics/sas/SASRectangleClass';

it('registers the Java Sprite turn(x, y, angle) bridge used by Windrad', async () => {
    const compiler = new JavaCompiler();
    const libraries = new JavaLibraryManager();
    libraries.addLibraries('sas');
    libraries.addLibrariesToCompiler(compiler);
    const file = new CompilerFile('Windrad.java');
    file.setText(`import sas.*;
        class Windrad {
            Sprite rotor;
            double drehPunktX = 100;
            double drehPunktY = 100;
            void dreheRotor(double geschwindigkeit) {
                rotor.turn(drehPunktX, drehPunktY, geschwindigkeit);
            }
        }
        Windrad w = new Windrad();`);
    compiler.setFiles([file]);
    const executable = await compiler.compileIfDirty();
    expect(executable).toBeDefined();
    expect(executable!.getAllErrors().filter(e => e.level === 'error')).toEqual([]);

    const world = { app: { stage: new PIXI.Container() }, shapesWhichBelongToNoGroup: [] };
    const t = { s: [], scheduler: { interpreter: { retrieveObject: () => world } } } as unknown as Thread;
    const blade = new SASRectangleClass();
    blade._cj$_constructor_$Rectangle$double$double$double$double(t, undefined, 100, 90, 50, 20);
    t.s.pop();
    const rotor = new SASSpriteClass();
    rotor._cj$_constructor_$Sprite$Shapes(t, undefined, blade);
    t.s.pop();
    const bridge = (rotor as unknown as Record<string, Function>)._mj$turn$void$double$double$double;
    expect(bridge).toBeTypeOf('function');
    const callback = vi.fn();
    bridge.call(rotor, t, callback, 100, 100, 90);
    expect(callback).toHaveBeenCalledTimes(1);
    const point = rotor.getWorldTransform().apply(new PIXI.Point(150, 100));
    expect(point.x).toBeCloseTo(100);
    expect(point.y).toBeCloseTo(150);
    expect(t.s).toHaveLength(0);
});
