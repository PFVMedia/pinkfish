<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';
import { Menu, X } from 'lucide-vue-next';
import ParallaxOrbs from '@/components/ParallaxOrbs.vue';
import ParallaxCubes from '@/components/ParallaxCubes.vue';
import PastelBackground from '@/components/PastelBackground.vue';
import { pastelColor } from '@/lib/pastels';

const page = usePage();
const mobileMenuOpen = ref(false);

const settings = computed(() => (page.props.siteSettings as Record<string, unknown>) || {});
const logoText = computed(() => (settings.value.logo_text as string) || 'Pink Fish');
const tagline = computed(() => (settings.value.brand_tagline as string) || 'Building modern web applications with precision and care.');
const copyrightText = computed(() => (settings.value.copyright_text as string) || 'Pink Fish Ventures Inc. All rights reserved.');
const ctaText = computed(() => (settings.value.cta_text as string) || 'Get Started');
const ctaUrl = computed(() => (settings.value.cta_url as string) || '/contact');
const colorPalette = computed(() => (settings.value.color_palette as string) || 'indigo');
const backgroundStyle = computed(() => (settings.value.background_style as string) || 'orbs');

function vars(hsl: string): Record<string, string> {
    return { '--primary': hsl, '--ring': hsl, '--sidebar-primary': hsl, '--sidebar-ring': hsl };
}

const palettes: Record<string, { light: Record<string, string>; dark: Record<string, string> }> = {
    indigo: { light: vars('hsl(245 70% 57%)'), dark: vars('hsl(245 75% 65%)') },
    blue: { light: vars('hsl(217 95% 54%)'), dark: vars('hsl(217 95% 62%)') },
    sky: { light: vars('hsl(199 92% 52%)'), dark: vars('hsl(199 92% 60%)') },
    teal: { light: vars('hsl(172 72% 42%)'), dark: vars('hsl(172 72% 52%)') },
    emerald: { light: vars('hsl(152 68% 42%)'), dark: vars('hsl(152 68% 52%)') },
    amber: { light: vars('hsl(38 95% 50%)'), dark: vars('hsl(38 95% 58%)') },
    orange: { light: vars('hsl(25 98% 52%)'), dark: vars('hsl(25 98% 60%)') },
    rose: { light: vars('hsl(347 82% 54%)'), dark: vars('hsl(347 82% 62%)') },
    pink: { light: vars('hsl(330 85% 56%)'), dark: vars('hsl(330 85% 64%)') },
    purple: { light: vars('hsl(271 82% 58%)'), dark: vars('hsl(271 82% 66%)') },
    slate: { light: vars('hsl(215 20% 40%)'), dark: vars('hsl(215 20% 58%)') },
};

const isDark = ref(document.documentElement.classList.contains('dark'));

let observer: MutationObserver | null = null;
onMounted(() => {
    observer = new MutationObserver(() => {
        isDark.value = document.documentElement.classList.contains('dark');
    });
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
});
onUnmounted(() => observer?.disconnect());

function applyPalette(palette: string, dark: boolean) {
    const p = palettes[palette] || palettes.indigo;
    const vars = dark ? p.dark : p.light;
    const root = document.documentElement;
    Object.entries(vars).forEach(([key, value]) => root.style.setProperty(key, value));
}

watch([colorPalette, isDark], ([palette, dark]) => applyPalette(palette, dark), { immediate: true });

const isPastel = computed(() => backgroundStyle.value === 'pastel');
const headerStyle = computed(() => (isPastel.value ? { backgroundColor: pastelColor(colorPalette.value, 'header', isDark.value) } : {}));
const footerStyle = computed(() => (isPastel.value ? { backgroundColor: pastelColor(colorPalette.value, 'footer', isDark.value) } : {}));
const mobileMenuStyle = computed(() => (isPastel.value ? { backgroundColor: pastelColor(colorPalette.value, 'header', isDark.value) } : {}));

const navLinks = computed(() => {
    const nav = settings.value.main_nav;
    if (Array.isArray(nav) && nav.length > 0) return nav as Array<{ name: string; href: string }>;
    return [
        { name: 'Services', href: '/services' },
        { name: 'Tools', href: '/tools' },
        { name: 'Blog', href: '/blog' },
        { name: 'Links', href: '/links' },
    ];
});

const footerColumns = computed(() => {
    const cols = settings.value.footer_columns;
    if (Array.isArray(cols) && cols.length > 0) return cols as Array<{ title: string; links: Array<{ name: string; href: string }> }>;
    return [
        { title: 'Navigation', links: [{ name: 'About', href: '/about' }, { name: 'Services', href: '/services' }, { name: 'Tools', href: '/tools' }, { name: 'Blog', href: '/blog' }] },
        { title: 'Resources', links: [{ name: 'Links', href: '/links' }, { name: 'Contact', href: '/contact' }] },
        { title: 'Legal', links: [{ name: 'Privacy Policy', href: '/privacy' }, { name: 'Disclaimer', href: '/disclaimer' }] },
    ];
});

function isActive(href: string): boolean {
    if (href === '/') return page.url === '/';
    return page.url.startsWith(href);
}
</script>

<template>
    <div class="relative flex min-h-screen flex-col text-foreground">
        <ParallaxCubes v-if="backgroundStyle === 'cubes'" />
        <PastelBackground v-else-if="backgroundStyle === 'pastel'" :palette="colorPalette" :dark="isDark" />
        <ParallaxOrbs v-else />

        <!-- Navigation -->
        <header
            class="fixed top-0 z-50 w-full border-b border-border/50 backdrop-blur-xl"
            :class="isPastel ? '' : 'bg-background/80'"
            :style="headerStyle"
        >
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <Link href="/" class="flex items-center gap-2.5">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary">
                        <span class="text-[10px] font-bold tracking-tight text-primary-foreground">PFV</span>
                    </div>
                    <span class="text-lg font-semibold tracking-tight">{{ logoText }}</span>
                </Link>

                <nav class="hidden items-center gap-1 md:flex">
                    <Link
                        v-for="link in navLinks"
                        :key="link.href"
                        :href="link.href"
                        class="rounded-full px-4 py-2 text-sm font-medium transition-colors hover:text-foreground"
                        :class="isActive(link.href) ? 'text-foreground' : 'text-muted-foreground'"
                    >
                        {{ link.name }}
                    </Link>
                </nav>

                <div class="flex items-center gap-3">
                    <Link
                        :href="ctaUrl"
                        class="hidden rounded-full border border-border bg-background px-5 py-2 text-sm font-medium shadow-sm transition-all hover:border-primary/30 hover:shadow-md sm:inline-flex"
                    >
                        {{ ctaText }}
                    </Link>
                    <button
                        class="inline-flex items-center justify-center rounded-full p-2 text-muted-foreground hover:bg-accent md:hidden"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                    >
                        <Menu v-if="!mobileMenuOpen" class="h-5 w-5" />
                        <X v-else class="h-5 w-5" />
                    </button>
                </div>
            </div>

            <div
                v-if="mobileMenuOpen"
                class="border-t border-border/50 backdrop-blur-xl md:hidden"
                :class="isPastel ? '' : 'bg-background/95'"
                :style="mobileMenuStyle"
            >
                <nav class="flex flex-col gap-1 px-4 py-4">
                    <Link
                        v-for="link in navLinks"
                        :key="link.href"
                        :href="link.href"
                        class="rounded-lg px-4 py-2.5 text-sm font-medium transition-colors hover:bg-accent"
                        :class="isActive(link.href) ? 'text-foreground bg-accent' : 'text-muted-foreground'"
                        @click="mobileMenuOpen = false"
                    >
                        {{ link.name }}
                    </Link>
                    <Link
                        :href="ctaUrl"
                        class="mt-2 rounded-lg bg-primary px-4 py-2.5 text-center text-sm font-medium text-primary-foreground"
                        @click="mobileMenuOpen = false"
                    >
                        {{ ctaText }}
                    </Link>
                </nav>
            </div>
        </header>

        <div class="h-16" />

        <div v-if="page.props.flash?.success" class="border-b border-green-200 bg-green-50 px-4 py-3 text-center text-sm text-green-800 dark:border-green-800 dark:bg-green-950 dark:text-green-200">
            {{ page.props.flash.success }}
        </div>
        <div v-if="page.props.flash?.error" class="border-b border-red-200 bg-red-50 px-4 py-3 text-center text-sm text-red-800 dark:border-red-800 dark:bg-red-950 dark:text-red-200">
            {{ page.props.flash.error }}
        </div>

        <main class="flex-1" style="position: relative; z-index: 10">
            <slot />
        </main>

        <!-- Footer -->
        <footer
            class="border-t border-border/50"
            :class="isPastel ? '' : 'bg-muted/30'"
            :style="{ ...footerStyle, position: 'relative', zIndex: 10 }"
        >
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="sm:col-span-2 lg:col-span-1">
                        <Link href="/" class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary">
                                <span class="text-[10px] font-bold tracking-tight text-primary-foreground">PFV</span>
                            </div>
                            <span class="text-lg font-semibold">{{ logoText }}</span>
                        </Link>
                        <p class="mt-4 max-w-xs text-sm leading-relaxed text-muted-foreground">{{ tagline }}</p>
                    </div>

                    <div v-for="column in footerColumns" :key="column.title">
                        <h4 class="text-sm font-semibold">{{ column.title }}</h4>
                        <nav class="mt-4 flex flex-col gap-2.5">
                            <Link v-for="link in column.links" :key="link.href" :href="link.href" class="text-sm text-muted-foreground transition hover:text-foreground">
                                {{ link.name }}
                            </Link>
                        </nav>
                    </div>
                </div>

                <div class="mt-12 border-t border-border/50 pt-8">
                    <p class="text-center text-sm text-muted-foreground">
                        &copy; {{ new Date().getFullYear() }} {{ copyrightText }}
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
