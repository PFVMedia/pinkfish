<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue';

const scrollY = ref(0);
const mouseX = ref(0);
const mouseY = ref(0);

let ticking = false;
function onScroll() {
    if (!ticking) {
        requestAnimationFrame(() => {
            scrollY.value = window.scrollY;
            ticking = false;
        });
        ticking = true;
    }
}

function onMouseMove(e: MouseEvent) {
    mouseX.value = (e.clientX / window.innerWidth - 0.5) * 2;
    mouseY.value = (e.clientY / window.innerHeight - 0.5) * 2;
}

onMounted(() => {
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('mousemove', onMouseMove, { passive: true });
});

onUnmounted(() => {
    window.removeEventListener('scroll', onScroll);
    window.removeEventListener('mousemove', onMouseMove);
});

interface Orb {
    id: number;
    size: number;
    x: string;
    y: string;
    speed: number;
    mouseInfluence: number;
    opacity: number;
    hue: number;
    orbitClass: string;
}

const orbs: Orb[] = [
    { id: 1, size: 380, x: '8%', y: '8%', speed: 0.04, mouseInfluence: 20, opacity: 0.75, hue: 0, orbitClass: 'orbit-1' },
    { id: 2, size: 320, x: '72%', y: '12%', speed: -0.05, mouseInfluence: 15, opacity: 0.7, hue: 60, orbitClass: 'orbit-2' },
    { id: 3, size: 280, x: '45%', y: '55%', speed: 0.03, mouseInfluence: 25, opacity: 0.65, hue: -45, orbitClass: 'orbit-3' },
    { id: 4, size: 220, x: '18%', y: '42%', speed: -0.06, mouseInfluence: 30, opacity: 0.8, hue: 120, orbitClass: 'orbit-4' },
    { id: 5, size: 200, x: '82%', y: '62%', speed: 0.05, mouseInfluence: 28, opacity: 0.75, hue: -90, orbitClass: 'orbit-5' },
    { id: 6, size: 240, x: '6%', y: '72%', speed: -0.04, mouseInfluence: 22, opacity: 0.7, hue: 30, orbitClass: 'orbit-6' },
    { id: 7, size: 140, x: '55%', y: '18%', speed: 0.07, mouseInfluence: 40, opacity: 0.85, hue: 180, orbitClass: 'orbit-7' },
    { id: 8, size: 120, x: '30%', y: '82%', speed: -0.08, mouseInfluence: 45, opacity: 0.8, hue: -120, orbitClass: 'orbit-8' },
    { id: 9, size: 100, x: '88%', y: '42%', speed: 0.09, mouseInfluence: 50, opacity: 0.85, hue: 90, orbitClass: 'orbit-9' },
];

function orbStyle(orb: Orb): Record<string, string> {
    const scrollOffset = scrollY.value * orb.speed;
    const mx = mouseX.value * orb.mouseInfluence;
    const my = mouseY.value * orb.mouseInfluence;

    return {
        width: `${orb.size}px`,
        height: `${orb.size}px`,
        left: orb.x,
        top: orb.y,
        opacity: String(orb.opacity),
        filter: `hue-rotate(${orb.hue}deg)`,
        '--scroll-x': `${mx}px`,
        '--scroll-y': `${scrollOffset + my}px`,
    };
}
</script>

<template>
    <div class="parallax-orbs" aria-hidden="true">
        <div
            v-for="orb in orbs"
            :key="orb.id"
            class="orb"
            :class="orb.orbitClass"
            :style="orbStyle(orb)"
        />
    </div>
</template>

<style scoped>
.parallax-orbs {
    position: fixed;
    inset: 0;
    z-index: 0;
    overflow: hidden;
    pointer-events: none;
}

.orb {
    position: absolute;
    border-radius: 9999px;
    will-change: transform;
    background: radial-gradient(
        circle at 35% 35%,
        color-mix(in srgb, var(--primary) 95%, white) 0%,
        var(--primary) 35%,
        color-mix(in srgb, var(--primary) 70%, black) 75%,
        color-mix(in srgb, var(--primary) 40%, transparent) 100%
    );
    box-shadow:
        inset -20px -20px 60px color-mix(in srgb, var(--primary) 50%, black),
        inset 15px 15px 40px color-mix(in srgb, var(--primary) 80%, white),
        0 0 80px color-mix(in srgb, var(--primary) 40%, transparent);
    transform: translate3d(var(--scroll-x, 0), var(--scroll-y, 0), 0);
}

@keyframes orbit-a {
    0%   { margin-left: 0;     margin-top: 0; }
    25%  { margin-left: 40px;  margin-top: -30px; }
    50%  { margin-left: 0;     margin-top: -60px; }
    75%  { margin-left: -40px; margin-top: -30px; }
    100% { margin-left: 0;     margin-top: 0; }
}

@keyframes orbit-b {
    0%   { margin-left: 0;     margin-top: 0; }
    25%  { margin-left: -50px; margin-top: 40px; }
    50%  { margin-left: -100px; margin-top: 0; }
    75%  { margin-left: -50px; margin-top: -40px; }
    100% { margin-left: 0;     margin-top: 0; }
}

@keyframes orbit-c {
    0%   { margin-left: 0;    margin-top: 0; }
    33%  { margin-left: 60px; margin-top: 30px; }
    66%  { margin-left: 30px; margin-top: -50px; }
    100% { margin-left: 0;    margin-top: 0; }
}

.orbit-1 { animation: orbit-a 25s ease-in-out infinite; }
.orbit-2 { animation: orbit-b 30s ease-in-out infinite; }
.orbit-3 { animation: orbit-c 28s ease-in-out infinite; }
.orbit-4 { animation: orbit-a 22s ease-in-out infinite reverse; }
.orbit-5 { animation: orbit-b 26s ease-in-out infinite reverse; }
.orbit-6 { animation: orbit-c 32s ease-in-out infinite; }
.orbit-7 { animation: orbit-a 18s ease-in-out infinite; }
.orbit-8 { animation: orbit-b 20s ease-in-out infinite reverse; }
.orbit-9 { animation: orbit-c 24s ease-in-out infinite; }
</style>
