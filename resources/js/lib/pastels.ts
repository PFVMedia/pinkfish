type PastelHue = { h: number; s: number };

const hues: Record<string, PastelHue> = {
    indigo: { h: 245, s: 85 },
    blue: { h: 217, s: 90 },
    sky: { h: 199, s: 92 },
    teal: { h: 172, s: 70 },
    emerald: { h: 152, s: 65 },
    amber: { h: 45, s: 95 },
    orange: { h: 28, s: 100 },
    rose: { h: 347, s: 92 },
    pink: { h: 330, s: 92 },
    purple: { h: 271, s: 85 },
    slate: { h: 215, s: 30 },
};

const lightLightness = { background: 94, header: 88, footer: 86 };
const darkLightness = { background: 12, header: 16, footer: 18 };
const darkSaturation = 32;

export type PastelLayer = 'background' | 'header' | 'footer';

export function pastelColor(palette: string, layer: PastelLayer, dark: boolean): string {
    const hue = hues[palette] ?? hues.indigo;
    if (dark) {
        return `hsl(${hue.h} ${darkSaturation}% ${darkLightness[layer]}%)`;
    }
    return `hsl(${hue.h} ${hue.s}% ${lightLightness[layer]}%)`;
}
