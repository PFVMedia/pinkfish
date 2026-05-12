<script setup lang="ts">
import SeoHead from '@/components/SeoHead.vue';
import BlockRenderer from '@/components/blocks/BlockRenderer.vue';
import { useScrollReveal } from '@/composables/useScrollReveal';

defineProps<{
    page: { title: string; blocks: Array<{ type: string; data: Record<string, unknown> }> } | null;
    seo?: Record<string, unknown> | null;
}>();

useScrollReveal();
</script>

<template>
    <SeoHead :seo="seo" />

    <div>
        <BlockRenderer v-if="page?.blocks" :blocks="page.blocks" />
    </div>
</template>
