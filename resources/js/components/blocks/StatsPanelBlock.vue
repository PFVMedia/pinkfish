<script setup lang="ts">
import { resolveIcon } from '@/composables/useIconResolver';

defineProps<{
    heading?: string;
    stats?: Array<{ icon?: string; label: string; value: string; badge_text?: string }>;
}>();
</script>

<template>
    <div class="relative mx-auto mt-20 max-w-5xl px-4 sm:px-6 lg:px-8">
        <div class="animate-scale-in rounded-2xl border border-white/10 bg-[#1a1a2e] p-6 shadow-2xl shadow-primary/10 sm:p-8" style="animation-delay: 0.4s">
            <div v-if="heading" class="mb-6 flex items-center justify-between">
                <h3 class="text-xl font-semibold text-white">{{ heading }}</h3>
                <div class="flex gap-2">
                    <div class="h-3 w-3 rounded-full bg-white/20" />
                    <div class="h-3 w-3 rounded-full bg-white/20" />
                </div>
            </div>

            <div v-if="stats?.length" class="grid gap-4 sm:grid-cols-2">
                <div v-for="(stat, i) in stats" :key="i" class="rounded-xl border border-white/10 bg-white/5 p-5">
                    <div class="flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/20">
                            <component :is="resolveIcon(stat.icon)" v-if="resolveIcon(stat.icon)" class="h-4 w-4 text-primary" />
                        </div>
                        <span class="text-sm text-white/60">{{ stat.label }}</span>
                    </div>
                    <p class="mt-3 text-3xl font-bold text-white">{{ stat.value }}</p>
                    <span v-if="stat.badge_text" class="mt-1 inline-block rounded-full bg-emerald-500/10 px-2 py-0.5 text-xs text-emerald-400">{{ stat.badge_text }}</span>
                </div>
            </div>

            <div class="mt-6 flex items-end justify-between gap-1.5 px-2">
                <div v-for="(h, i) in [40, 65, 45, 80, 55, 70, 90, 60, 75, 50, 85, 95]" :key="i"
                    class="flex-1 rounded-t-sm bg-gradient-to-t from-primary/40 to-primary/80 transition-all"
                    :style="{ height: h + 'px' }"
                />
            </div>
        </div>
    </div>
</template>
