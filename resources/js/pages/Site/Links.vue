<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import BlockRenderer from '@/components/blocks/BlockRenderer.vue';
import { useScrollReveal } from '@/composables/useScrollReveal';

defineProps<{
    page: { title: string; blocks: Array<{ type: string; data: Record<string, unknown> }> } | null;
    links: Array<{ id: number; title: string; url: string; description?: string; category?: string; sort_order: number }>;
}>();

useScrollReveal();
</script>

<template>
    <Head title="Links" />

    <div>
        <BlockRenderer v-if="page?.blocks" :blocks="page.blocks" :extra="{ links }" />
    </div>
</template>
