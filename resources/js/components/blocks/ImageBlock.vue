<script setup lang="ts">
defineProps<{
    image: string;
    alt_text?: string;
    caption?: string;
}>();
</script>

<template>
    <section class="py-12 sm:py-16">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <figure class="reveal">
                <img :src="`/storage/${image}`" :alt="alt_text || ''" class="w-full rounded-2xl border border-border/60 shadow-lg" />
                <figcaption v-if="caption" class="mt-4 text-center text-sm text-muted-foreground">{{ caption }}</figcaption>
            </figure>
        </div>
    </section>
</template>
