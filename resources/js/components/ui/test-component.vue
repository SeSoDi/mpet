<template>
  <div class="border-2 border-red-500 p-4 bg-yellow-100">
    <h3>TEST COMPONENT LOADED!</h3>
    <p>Value received: {{ modelValue }}</p>
    <p>Type: {{ typeof modelValue }}</p>
  </div>
</template>

<script setup lang="ts">
interface Props {
  modelValue?: number
}

const props = defineProps<Props>()
</script>