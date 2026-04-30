<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import BlockRenderer from '@/components/blocks/BlockRenderer.vue';
import { useScrollReveal } from '@/composables/useScrollReveal';

defineProps<{
    page: { title: string; blocks: Array<{ type: string; data: Record<string, unknown> }> } | null;
}>();

useScrollReveal();
</script>

<template>
    <Head title="About" />

    <div>
        <BlockRenderer v-if="page?.blocks" :blocks="page.blocks" />
    </div>
</template>
