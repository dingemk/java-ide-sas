import { Thread } from "../../../../common/interpreter/Thread";
import { ThreadState } from "../../../../common/interpreter/ThreadState";

export function showSASDialog(t: Thread, message: string, title: string, mode: boolean | "input"): void {
    const dialog = document.createElement("dialog");
    dialog.className = "sas-tools-dialog";
    dialog.setAttribute("aria-label", title || "Bestaetigung");
    Object.assign(dialog.style, {
        width: "min(400px, calc(100vw - 48px))", boxSizing: "border-box",
        padding: "20px", border: "1px solid #777", borderRadius: "6px",
        background: "#ffffff", color: "#111111", font: "16px/1.5 Arial, sans-serif"
    });
    const heading = document.createElement("strong");
    heading.textContent = title || "Bestaetigung";
    const text = document.createElement("p");
    text.textContent = message;
    Object.assign(text.style, { whiteSpace: "pre-wrap", overflowWrap: "anywhere", maxHeight: "50vh", overflow: "auto" });
    const actions = document.createElement("div");
    Object.assign(actions.style, { display: "flex", gap: "8px", flexWrap: "wrap", justifyContent: "flex-end" });
    dialog.append(heading, text, actions);
    const input = mode === "input" ? document.createElement("input") : null;
    if (input) {
        input.type = "text";
        input.setAttribute("aria-label", message || "Eingabe");
        Object.assign(input.style, { width: "100%", boxSizing: "border-box", minHeight: "36px", marginBottom: "16px", font: "inherit" });
        dialog.insertBefore(input, actions);
        input.addEventListener("keydown", event => {
            if (event.key === "Enter" && !event.isComposing) {
                event.preventDefault();
                finish(input.value);
            }
        });
    }
    const events = t.scheduler.interpreter.eventManager;
    const previousFocus = document.activeElement as HTMLElement | null;
    let completed = false;
    const cleanup = () => {
        dialog.remove();
        events.off(stop);
        events.off(reset);
    };
    const stop = () => { completed = true; cleanup(); };
    const reset = () => { completed = true; cleanup(); };
    const finish = (answer: number | string | null) => {
        if (completed) return;
        completed = true;
        cleanup();
        if (mode !== false) t.s.push(answer);
        t.state = ThreadState.running;
        previousFocus?.focus();
    };
    const buttons: [string, number][] = mode === "input" ? [["OK", 0], ["Abbrechen", -1]]
        : mode ? [["Ja", 0], ["Nein", 1], ["Schliessen", -1]] : [["OK", 0]];
    for (const [label, answer] of buttons) {
        const button = document.createElement("button");
        button.type = "button";
        button.textContent = label;
        Object.assign(button.style, { padding: "6px 14px", minHeight: "36px", border: "1px solid #777", borderRadius: "4px", background: "#f4f4f4", color: "#111", font: "inherit", cursor: "pointer" });
        button.onclick = () => finish(input ? (answer === 0 ? input.value : null) : answer);
        actions.appendChild(button);
    }
    dialog.addEventListener("cancel", event => { event.preventDefault(); finish(input ? null : -1); });
    for (const kind of ["keydown", "keyup", "keypress", "mousedown", "mouseup", "click"]) {
        dialog.addEventListener(kind, event => event.stopPropagation());
    }
    events.on("stop", stop);
    events.on("resetRuntime", reset);
    document.body.appendChild(dialog);
    t.state = ThreadState.waiting;
    if (typeof dialog.showModal === "function") dialog.showModal();
    else dialog.setAttribute("open", "");
    (input ?? actions.querySelector<HTMLButtonElement>("button"))?.focus();
}
