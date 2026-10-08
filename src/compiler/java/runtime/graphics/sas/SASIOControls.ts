import { Thread } from "../../../../common/interpreter/Thread";
import { WorldClass } from "../WorldClass";
import { SASViewClass } from "./SASViewClass";

type Control = HTMLButtonElement | HTMLInputElement;

export function withSASIOWorld(t: Thread, ready: (world: WorldClass) => void): void {
    const world = t.scheduler.interpreter.retrieveObject("WorldClass") as WorldClass | undefined;
    if (world) return ready(world);
    new SASViewClass()._cj$_constructor_$View$(t, () => ready(t.s.pop()));
}

export function attachSASIOControl(world: WorldClass, element: Control,
    x: number, y: number, width: number, height: number): void {
    const parent = world.graphicsDiv!;
    parent.style.position = "relative";
    Object.assign(element.style, {
        position: "absolute", boxSizing: "border-box", left: `${x}px`, top: `${y}px`,
        width: `${width}px`, height: `${height}px`, margin: "0", borderRadius: "2px",
        fontFamily: "Arial, sans-serif", fontSize: `${Math.max(1, Math.floor(height * 0.5))}px`,
        transformOrigin: "top left", color: "#000000", border: "1px solid #333333",
        padding: "1px 3px", letterSpacing: "0"
    });
    let previousScale: number | undefined;
    const resize = () => {
        const canvas = world.app?.canvas;
        if (!canvas) return;
        const scale = (parseFloat(canvas.style.width) || world.width) / world.width;
        if (scale === previousScale) return;
        previousScale = scale;
        element.style.left = `${x * scale}px`;
        element.style.top = `${y * scale}px`;
        element.style.transform = `scale(${scale})`;
    };
    // Keep native editing keys away from the IDE's shortcuts and game keyboard state.
    for (const kind of ["keydown", "keyup", "keypress", "mousedown", "mouseup", "click"]) {
        element.addEventListener(kind, event => event.stopPropagation());
    }
    parent.appendChild(element);
    const observer = typeof ResizeObserver === "undefined" ? undefined : new ResizeObserver(resize);
    if (world.app?.canvas) observer?.observe(world.app.canvas);
    // View.setSize can change the logical size without changing the canvas CSS size.
    world.app?.ticker?.add(resize);
    resize();
    const events = world.interpreter.eventManager;
    const stop = () => { element.disabled = true; element.blur(); };
    const reset = () => {
        observer?.disconnect();
        world.app?.ticker?.remove(resize);
        element.remove();
        events.off(stop);
        events.off(reset);
    };
    events.on("stop", stop);
    events.on("resetRuntime", reset);
}
