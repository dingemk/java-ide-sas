export function isWorkspaceImage(filename: string): boolean {
    return /\.(png|jpe?g)$/i.test(filename);
}

export function isWorkspaceImageData(text: string): boolean {
    return /^data:image\/(png|jpeg);base64,[A-Za-z0-9+/]+={0,2}$/.test(text);
}

export async function readWorkspaceImage(file: File): Promise<string> {
    if (!isWorkspaceImage(file.name)) throw new Error("Bitte eine PNG- oder JPG-Datei auswaehlen.");
    if (file.size > 10 * 1024 * 1024) throw new Error("Das Bild darf maximal 10 MB gross sein.");
    const bytes = new Uint8Array(await file.slice(0, 8).arrayBuffer());
    const png = [137, 80, 78, 71, 13, 10, 26, 10].every((byte, i) => bytes[i] === byte);
    const jpeg = bytes[0] === 255 && bytes[1] === 216 && bytes[2] === 255;
    if (!png && !jpeg) throw new Error("Die Datei ist kein PNG- oder JPG-Bild.");
    const data = await new Promise<string>((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => resolve(String(reader.result));
        reader.onerror = () => reject(new Error("Das Bild konnte nicht gelesen werden."));
        reader.readAsDataURL(new Blob([file], { type: png ? "image/png" : "image/jpeg" }));
    });
    await new Promise<void>((resolve, reject) => {
        const image = new Image();
        image.onload = () => resolve();
        image.onerror = () => reject(new Error("Das Bild ist beschaedigt oder nicht lesbar."));
        image.src = data;
    });
    return data;
}
