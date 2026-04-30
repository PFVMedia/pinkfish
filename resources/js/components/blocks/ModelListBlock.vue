<script setup lang="ts">
import { ExternalLink } from 'lucide-vue-next';

defineProps<{
    model_type: string;
    tools?: Array<{ id: number; name: string; description: string; url?: string; sort_order: number }>;
    links?: Array<{ id: number; title: string; url: string; description?: string; category?: string; sort_order: number }>;
}>();
</script>

<template>
    <!-- Tools list -->
    <section v-if="model_type === 'tools' && tools?.length" class="pb-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <a
                    v-for="(tool, idx) in tools"
                    :key="tool.id"
                    :href="tool.url || undefined"
                    :target="tool.url ? '_blank' : undefined"
                    :rel="tool.url ? 'noopener noreferrer' : undefined"
                    class="reveal group rounded-2xl border border-border/60 bg-gradient-to-b from-background to-muted/20 p-7 transition-all hover:border-primary/20 hover:shadow-lg hover:shadow-primary/5"
                    :class="`reveal-delay-${(idx % 3) + 1}`"
                >
                    <div class="flex items-start justify-between">
                        <h3 class="text-lg font-semibold transition-colors group-hover:text-primary">{{ tool.name }}</h3>
                        <ExternalLink v-if="tool.url" class="h-4 w-4 shrink-0 text-muted-foreground transition-colors group-hover:text-primary" />
                    </div>
                    <p class="mt-3 text-sm leading-relaxed text-muted-foreground">{{ tool.description }}</p>
                </a>
            </div>
        </div>
    </section>

    <!-- Links list (grouped by category) -->
    <section v-if="model_type === 'links' && links?.length" class="pb-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div v-for="(group, category) in groupByCategory(links)" :key="String(category)" class="reveal mb-12 last:mb-0">
                <h2 v-if="category" class="mb-6 text-2xl font-bold">{{ category }}</h2>
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    <a
                        v-for="(link, idx) in group"
                        :key="link.id"
                        :href="link.url"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="reveal group rounded-2xl border border-border/60 bg-gradient-to-b from-background to-muted/20 p-7 transition-all hover:border-primary/20 hover:shadow-lg hover:shadow-primary/5"
                        :class="`reveal-delay-${(idx % 3) + 1}`"
                    >
                        <div class="flex items-start justify-between">
                            <h3 class="text-lg font-semibold transition-colors group-hover:text-primary">{{ link.title }}</h3>
                            <ExternalLink class="h-4 w-4 shrink-0 text-muted-foreground transition-colors group-hover:text-primary" />
                        </div>
                        <p v-if="link.description" class="mt-3 text-sm leading-relaxed text-muted-foreground">{{ link.description }}</p>
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>

<script lang="ts">
function groupByCategory(links: Array<{ category?: string;[key: string]: unknown }>): Record<string, typeof links> {
    const groups: Record<string, typeof links> = {};
    for (const link of links) {
        const cat = link.category || 'General';
        if (!groups[cat]) groups[cat] = [];
        groups[cat].push(link);
    }
    return groups;
}
</script>
