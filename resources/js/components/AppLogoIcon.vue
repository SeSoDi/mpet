<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
    variant?: 'main' | 'favicon';
}

const props = defineProps<Props>();
</script>

<template>
    <template v-if="props.variant === 'favicon'">
        <!-- Favicon SVG -->
        <svg width="64" height="64" viewBox="0 0 64 64" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <!-- Replace the content below with your actual favicon SVG paths -->
            <!-- Example placeholder favicon SVG: -->
            <circle cx="32" cy="32" r="28" fill="#222"/>
            <text x="32" y="40" text-anchor="middle" font-size="28" fill="#fff" font-family="Arial" dy=".3em">M</text>
        </svg>
    </template>
    <template v-else>
        <!-- Main logo SVG -->
        <svg width="64" height="64" viewBox="0 0 64 64" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
            <!-- Face outline -->
            <ellipse cx="32" cy="36" rx="18" ry="16"/>
            <!-- Left ear -->
            <path d="M14 24 Q8 8 24 16"/>
            <!-- Right ear -->
            <path d="M50 24 Q56 8 40 16"/>
            <!-- Left eye -->
            <circle cx="25" cy="36" r="2"/>
            <!-- Right eye -->
            <circle cx="39" cy="36" r="2"/>
            <!-- Nose -->
            <ellipse cx="32" cy="42" rx="2" ry="1.2"/>
            <!-- Mouth -->
            <path d="M32 43 Q30 46 28 44"/>
            <path d="M32 43 Q34 46 36 44"/>
        </svg>
    </template>
</template>
