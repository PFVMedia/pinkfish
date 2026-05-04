<script setup lang="ts">
import { computed } from 'vue';
import { pastelColor } from '@/lib/pastels';

const props = defineProps<{
    palette: string;
    dark: boolean;
}>();

const color = computed(() => pastelColor(props.palette, 'background', props.dark));
</script>

<template>
    <div class="pointer-events-none fixed inset-0" :style="{ backgroundColor: color, zIndex: 0 }" />
</template>
