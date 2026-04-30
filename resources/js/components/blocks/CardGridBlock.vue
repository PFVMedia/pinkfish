<script setup lang="ts">
import { resolveIcon } from '@/composables/useIconResolver';

defineProps<{
    badge_text?: string;
    heading: string;
    subheading?: string;
    columns?: number;
    cards?: Array<{ icon?: string; title: string; description: string; url?: string }>;
}>();
</script>

<template>
    <section class="relative py-24 sm:py-32">
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute left-1/4 top-1/2 h-[400px] w-[600px] -translate-y-1/2 rounded-full blur-[120px]" style="background: radial-gradient(ellipse, color-mix(in srgb, var(--primary) 25%, transparent), transparent)" />
        </div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="reveal mx-auto max-w-2xl text-center">
                <span v-if="badge_text" class="inline-flex rounded-full border border-border/80 bg-muted/50 px-4 py-1.5 text-sm text-muted-foreground">
                    {{ badge_text }}
                </span>
                <h2 class="mt-6 text-4xl font-bold tracking-tight sm:text-5xl">{{ heading }}</h2>
                <p v-if="subheading" class="mt-4 text-lg text-muted-foreground">{{ subheading }}</p>
            </div>

            <div
                v-if="cards?.length"
                class="mt-16 grid gap-5 sm:grid-cols-2"
                :class="{ 'lg:grid-cols-3': (columns ?? 3) === 3, 'lg:grid-cols-4': columns === 4, 'lg:grid-cols-2': columns === 2 }"
            >
                <component
                    :is="card.url ? 'a' : 'div'"
                    v-for="(card, idx) in cards"
                    :key="idx"
                    :href="card.url || undefined"
                    :target="card.url ? '_blank' : undefined"
                    :rel="card.url ? 'noopener noreferrer' : undefined"
                    class="reveal group rounded-2xl border border-border/60 bg-gradient-to-b from-background to-muted/20 p-7 transition-all hover:border-primary/20 hover:shadow-lg hover:shadow-primary/5"
                    :class="`reveal-delay-${(idx % 3) + 1}`"
                >
                    <div v-if="resolveIcon(card.icon)" class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10 transition-colors group-hover:bg-primary/15">
                        <component :is="resolveIcon(card.icon)" class="h-5 w-5 text-primary" />
                    </div>
                    <h3 class="mt-5 text-lg font-semibold">{{ card.title }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-muted-foreground">{{ card.description }}</p>
                </component>
            </div>
        </div>
    </section>
</template>
