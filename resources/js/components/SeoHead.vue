<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

interface SeoProps {
    title?: string | null;
}

const props = defineProps<{
    seo?: SeoProps | null;
}>();

const page = usePage();
const appName = computed(() => (page.props.name as string) || '');

const title = computed(() => {
    const t = props.seo?.title;
    if (!t) return appName.value;
    return appName.value && t !== appName.value ? `${t} – ${appName.value}` : t;
});
</script>

<template>
    <Head :title="title" />
</template>
