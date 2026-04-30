<script setup lang="ts">
defineProps<{
    content: string;
}>();
</script>

<template>
    <section class="py-12 sm:py-16">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="reveal prose prose-lg dark:prose-invert max-w-none" v-html="content" />
        </div>
    </section>
</template>
