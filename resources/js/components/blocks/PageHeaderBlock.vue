<script setup lang="ts">
defineProps<{
    badge_text?: string;
    heading: string;
    subheading?: string;
}>();
</script>

<template>
    <section class="relative overflow-hidden py-20 sm:py-28">
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute left-1/2 top-0 h-[400px] w-[700px] -translate-x-1/2 -translate-y-1/2 rounded-full blur-[120px]" style="background: radial-gradient(ellipse, color-mix(in srgb, var(--primary) 30%, transparent), transparent)" />
        </div>

        <div class="relative mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <span v-if="badge_text" class="animate-fade-in inline-flex rounded-full border border-border/80 bg-muted/50 px-4 py-1.5 text-sm text-muted-foreground">
                {{ badge_text }}
            </span>
            <h1 class="animate-fade-up mt-6 text-5xl font-bold tracking-tight sm:text-6xl">{{ heading }}</h1>
            <p v-if="subheading" class="animate-fade-up mt-4 text-lg text-muted-foreground" style="animation-delay: 0.1s">{{ subheading }}</p>
        </div>
    </section>
</template>
