<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowRight } from 'lucide-vue-next';

defineProps<{
    heading: string;
    body?: string;
    buttons?: Array<{ text: string; url: string; style?: string }>;
}>();
</script>

<template>
    <section class="relative overflow-hidden py-24 sm:py-32">
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute left-1/2 top-1/2 h-[500px] w-[800px] -translate-x-1/2 -translate-y-1/2 rounded-full blur-[120px]" style="background: radial-gradient(ellipse, color-mix(in srgb, var(--primary) 30%, transparent), transparent)" />
        </div>

        <div class="reveal relative mx-auto max-w-3xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold tracking-tight sm:text-5xl">{{ heading }}</h2>
            <p v-if="body" class="mt-4 text-lg text-muted-foreground">{{ body }}</p>
            <div v-if="buttons?.length" class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                <Link
                    v-for="(btn, i) in buttons"
                    :key="i"
                    :href="btn.url"
                    :class="btn.style === 'outline'
                        ? 'inline-flex items-center gap-2 rounded-full border border-border px-8 py-3.5 text-sm font-medium transition-all hover:border-primary/30 hover:bg-accent'
                        : 'inline-flex items-center gap-2 rounded-full bg-primary px-8 py-3.5 text-sm font-medium text-primary-foreground shadow-lg shadow-primary/25 transition-all hover:shadow-xl hover:shadow-primary/30'"
                >
                    {{ btn.text }}
                    <ArrowRight v-if="btn.style !== 'outline'" class="h-4 w-4" />
                </Link>
            </div>
        </div>
    </section>
</template>
