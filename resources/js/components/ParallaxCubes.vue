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

interface Cube {
    id: number;
    size: number;
    x: string;
    y: string;
    speed: number;
    mouseInfluence: number;
    opacity: number;
    hue: number;
    spinClass: string;
}

const cubes: Cube[] = [
    { id: 1, size: 320, x: '8%',  y: '8%',  speed: 0.04,  mouseInfluence: 20, opacity: 0.7,  hue: 0,    spinClass: 'spin-a' },
    { id: 2, size: 260, x: '72%', y: '12%', speed: -0.05, mouseInfluence: 15, opacity: 0.65, hue: 60,   spinClass: 'spin-b' },
    { id: 3, size: 220, x: '45%', y: '55%', speed: 0.03,  mouseInfluence: 25, opacity: 0.6,  hue: -45,  spinClass: 'spin-c' },
    { id: 4, size: 180, x: '18%', y: '42%', speed: -0.06, mouseInfluence: 30, opacity: 0.75, hue: 120,  spinClass: 'spin-a' },
    { id: 5, size: 170, x: '82%', y: '62%', speed: 0.05,  mouseInfluence: 28, opacity: 0.7,  hue: -90,  spinClass: 'spin-b' },
    { id: 6, size: 200, x: '6%',  y: '72%', speed: -0.04, mouseInfluence: 22, opacity: 0.65, hue: 30,   spinClass: 'spin-c' },
    { id: 7, size: 120, x: '55%', y: '18%', speed: 0.07,  mouseInfluence: 40, opacity: 0.8,  hue: 180,  spinClass: 'spin-a' },
    { id: 8, size: 100, x: '30%', y: '82%', speed: -0.08, mouseInfluence: 45, opacity: 0.75, hue: -120, spinClass: 'spin-b' },
    { id: 9, size: 90,  x: '88%', y: '42%', speed: 0.09,  mouseInfluence: 50, opacity: 0.8,  hue: 90,   spinClass: 'spin-c' },
];

function cubeWrapperStyle(cube: Cube): Record<string, string> {
    const scrollOffset = scrollY.value * cube.speed;
    const mx = mouseX.value * cube.mouseInfluence;
    const my = mouseY.value * cube.mouseInfluence;

    return {
        width: `${cube.size}px`,
        height: `${cube.size}px`,
        left: cube.x,
        top: cube.y,
        opacity: String(cube.opacity),
        filter: `hue-rotate(${cube.hue}deg)`,
        '--scroll-x': `${mx}px`,
        '--scroll-y': `${scrollOffset + my}px`,
        '--half': `${cube.size / 2}px`,
        '--neg-half': `-${cube.size / 2}px`,
    };
}
</script>

<template>
    <div class="parallax-cubes" aria-hidden="true">
        <div
            v-for="cube in cubes"
            :key="cube.id"
            class="cube-wrapper"
            :style="cubeWrapperStyle(cube)"
        >
            <div class="cube" :class="cube.spinClass">
                <div class="face front" />
                <div class="face back" />
                <div class="face right" />
                <div class="face left" />
                <div class="face top" />
                <div class="face bottom" />
            </div>
        </div>
    </div>
</template>

<style scoped>
.parallax-cubes {
    position: fixed;
    inset: 0;
    z-index: 0;
    overflow: hidden;
    pointer-events: none;
    perspective: 1200px;
}

.cube-wrapper {
    position: absolute;
    will-change: transform;
    transform-style: preserve-3d;
    transform: translate3d(var(--scroll-x, 0), var(--scroll-y, 0), 0);
}

.cube {
    position: relative;
    width: 100%;
    height: 100%;
    transform-style: preserve-3d;
}

.face {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        135deg,
        color-mix(in srgb, var(--primary) 90%, white) 0%,
        var(--primary) 50%,
        color-mix(in srgb, var(--primary) 60%, black) 100%
    );
    border: 1px solid color-mix(in srgb, var(--primary) 40%, transparent);
    box-shadow:
        inset 0 0 40px color-mix(in srgb, var(--primary) 60%, white),
        0 0 60px color-mix(in srgb, var(--primary) 30%, transparent);
}

.face.front  { transform: translateZ(var(--half)); }
.face.back   { transform: rotateY(180deg) translateZ(var(--half)); }
.face.right  { transform: rotateY(90deg)  translateZ(var(--half)); }
.face.left   { transform: rotateY(-90deg) translateZ(var(--half)); }
.face.top    { transform: rotateX(90deg)  translateZ(var(--half)); }
.face.bottom { transform: rotateX(-90deg) translateZ(var(--half)); }

@keyframes spin-a {
    0%   { transform: rotateX(0deg)   rotateY(0deg)   rotateZ(0deg); }
    100% { transform: rotateX(360deg) rotateY(360deg) rotateZ(0deg); }
}

@keyframes spin-b {
    0%   { transform: rotateX(0deg)    rotateY(0deg)   rotateZ(0deg); }
    100% { transform: rotateX(-360deg) rotateY(360deg) rotateZ(180deg); }
}

@keyframes spin-c {
    0%   { transform: rotateX(0deg)   rotateY(0deg)    rotateZ(0deg); }
    100% { transform: rotateX(360deg) rotateY(-360deg) rotateZ(360deg); }
}

.spin-a { animation: spin-a 40s linear infinite; }
.spin-b { animation: spin-b 50s linear infinite; }
.spin-c { animation: spin-c 60s linear infinite; }
</style>
