<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowRight, Zap } from 'lucide-vue-next';

defineProps<{
    badge_text?: string;
    heading: string;
    subtitle?: string;
    buttons?: Array<{ text: string; url: string; style?: string }>;
}>();
</script>

<template>
    <section class="relative overflow-hidden pb-20 pt-16 sm:pt-24 lg:pt-32">
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute left-1/2 top-1/2 h-[600px] w-[900px] -translate-x-1/2 -translate-y-1/3 rounded-full blur-[120px]" style="background: radial-gradient(ellipse, color-mix(in srgb, var(--primary) 35%, transparent), transparent); animation: glow-pulse 6s ease-in-out infinite" />
            <div class="absolute bottom-0 left-1/2 h-[300px] w-[700px] -translate-x-1/2 translate-y-1/4 rounded-full blur-[100px]" style="background: radial-gradient(ellipse at bottom, color-mix(in srgb, var(--primary) 40%, transparent), transparent)" />
        </div>

        <div class="relative mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <div v-if="badge_text" class="animate-fade-in inline-flex items-center gap-2 rounded-full border border-border/80 bg-muted/50 px-4 py-1.5 text-sm text-muted-foreground backdrop-blur-sm">
                <Zap class="h-3.5 w-3.5 text-primary" />
                {{ badge_text }}
            </div>

            <h1 class="animate-fade-up mt-8 text-5xl font-bold leading-[1.1] tracking-tight sm:text-6xl lg:text-7xl">
                {{ heading }}
            </h1>

            <p v-if="subtitle" class="animate-fade-up mx-auto mt-6 max-w-2xl text-lg leading-relaxed text-muted-foreground sm:text-xl" style="animation-delay: 0.15s">
                {{ subtitle }}
            </p>

            <div v-if="buttons?.length" class="animate-fade-up mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row" style="animation-delay: 0.3s">
                <Link
                    v-for="(btn, i) in buttons"
                    :key="i"
                    :href="btn.url"
                    :class="btn.style === 'outline'
                        ? 'inline-flex items-center gap-2 rounded-full border border-border px-7 py-3 text-sm font-medium transition-all hover:border-primary/30 hover:bg-accent'
                        : 'inline-flex items-center gap-2 rounded-full bg-primary px-7 py-3 text-sm font-medium text-primary-foreground shadow-lg shadow-primary/25 transition-all hover:shadow-xl hover:shadow-primary/30'"
                >
                    {{ btn.text }}
                    <ArrowRight v-if="btn.style !== 'outline'" class="h-4 w-4" />
                </Link>
            </div>
        </div>
    </section>
</template>
