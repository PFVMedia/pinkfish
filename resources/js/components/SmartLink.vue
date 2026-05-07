<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{ href: string }>();

const isExternal = computed(() => /^(https?:|mailto:|tel:)/i.test(props.href));
</script>

<template>
    <a v-if="isExternal" :href="href" target="_blank" rel="noopener noreferrer">
        <slot />
    </a>
    <Link v-else :href="href">
        <slot />
    </Link>
</template>
