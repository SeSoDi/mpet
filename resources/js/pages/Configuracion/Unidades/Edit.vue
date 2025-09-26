<script setup lang="ts">
// Re-usar el componente Create.vue para edición
import Create from './Create.vue'

// Este componente recibe las mismas props que Create
// La diferencia está en que recibe la prop 'unidad' del controlador
interface Props {
  unidad?: {
    id: number
    nombre: string
    activo: boolean
    descripcion?: string
    created_at: string
    updated_at: string
  } | null
}

const props = defineProps<Props>()
</script>

<template>
  <Create :unidad="props.unidad" />
</template>