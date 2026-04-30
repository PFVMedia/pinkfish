<script setup lang="ts">
import type { Component } from 'vue';
import BlogLatestBlock from './BlogLatestBlock.vue';
import CardGridBlock from './CardGridBlock.vue';
import ContactFormBlock from './ContactFormBlock.vue';
import CtaBlock from './CtaBlock.vue';
import HeroBlock from './HeroBlock.vue';
import ImageBlock from './ImageBlock.vue';
import ModelListBlock from './ModelListBlock.vue';
import PageHeaderBlock from './PageHeaderBlock.vue';
import RichTextBlock from './RichTextBlock.vue';
import StatsPanelBlock from './StatsPanelBlock.vue';
import WorkflowBlock from './WorkflowBlock.vue';

const blockComponents: Record<string, Component> = {
    hero: HeroBlock,
    stats_panel: StatsPanelBlock,
    workflow: WorkflowBlock,
    card_grid: CardGridBlock,
    rich_text: RichTextBlock,
    cta: CtaBlock,
    blog_latest: BlogLatestBlock,
    contact_form: ContactFormBlock,
    image: ImageBlock,
    page_header: PageHeaderBlock,
    model_list: ModelListBlock,
};

defineProps<{
    blocks: Array<{ type: string; data: Record<string, unknown> }>;
    extra?: Record<string, unknown>;
}>();
</script>

<template>
    <div>
        <template v-for="(block, index) in blocks" :key="index">
            <component
                v-if="blockComponents[block.type]"
                :is="blockComponents[block.type]"
                v-bind="{ ...block.data, ...extra }"
            />
        </template>
    </div>
</template>
