import { ref, onMounted, onUnmounted } from 'vue';

export function useScrollParallax() {
    const scrollY = ref(0);
    const viewportHeight = ref(0);
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

    function onResize() {
        viewportHeight.value = window.innerHeight;
    }

    onMounted(() => {
        scrollY.value = window.scrollY;
        viewportHeight.value = window.innerHeight;
        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', onResize, { passive: true });
    });

    onUnmounted(() => {
        window.removeEventListener('scroll', onScroll);
        window.removeEventListener('resize', onResize);
    });

    return { scrollY, viewportHeight };
}
