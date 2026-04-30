<script setup lang="ts">
import { resolveIcon } from '@/composables/useIconResolver';

defineProps<{
    badge_text?: string;
    heading: string;
    panels?: Array<{
        icon?: string;
        title: string;
        description?: string;
        style?: string;
        checklist_items?: Array<{ text: string }>;
    }>;
}>();
</script>

<template>
    <section class="relative py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="reveal mx-auto max-w-2xl text-center">
                <span v-if="badge_text" class="inline-flex rounded-full border border-border/80 bg-muted/50 px-4 py-1.5 text-sm text-muted-foreground">
                    {{ badge_text }}
                </span>
                <h2 class="mt-6 text-4xl font-bold tracking-tight sm:text-5xl">{{ heading }}</h2>
            </div>

            <div v-if="panels?.length" class="mt-16 grid gap-6 lg:grid-cols-2">
                <div
                    v-for="(panel, idx) in panels"
                    :key="idx"
                    class="reveal rounded-2xl border border-border/60 bg-gradient-to-b from-muted/30 to-muted/10 p-8 sm:p-10"
                    :class="idx > 0 ? `reveal-delay-${idx}` : ''"
                >
                    <div v-if="resolveIcon(panel.icon)" class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary/10">
                        <component :is="resolveIcon(panel.icon)" class="h-6 w-6 text-primary" />
                    </div>
                    <h3 class="mt-6 text-2xl font-semibold">{{ panel.title }}</h3>
                    <p v-if="panel.description" class="mt-3 leading-relaxed text-muted-foreground">{{ panel.description }}</p>

                    <!-- Code preview style -->
                    <div v-if="panel.style === 'preview'" class="mt-8 overflow-hidden rounded-xl border border-border/40 bg-[#1a1a2e] p-4">
                        <div class="flex gap-1.5">
                            <div class="h-2.5 w-2.5 rounded-full bg-red-400/60" />
                            <div class="h-2.5 w-2.5 rounded-full bg-yellow-400/60" />
                            <div class="h-2.5 w-2.5 rounded-full bg-green-400/60" />
                        </div>
                        <div class="mt-3 space-y-2">
                            <div class="h-3 w-3/4 rounded bg-white/10" />
                            <div class="h-3 w-1/2 rounded bg-white/10" />
                            <div class="h-3 w-5/6 rounded bg-primary/20" />
                            <div class="h-3 w-2/3 rounded bg-white/10" />
                        </div>
                    </div>

                    <!-- Checklist style -->
                    <div v-if="panel.style === 'checklist' && panel.checklist_items?.length" class="mt-8 space-y-3">
                        <div v-for="item in panel.checklist_items" :key="item.text"
                            class="flex items-center gap-3 rounded-lg border border-border/40 bg-background/50 px-4 py-3"
                        >
                            <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-500/10">
                                <svg class="h-3 w-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span class="text-sm">{{ item.text }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
