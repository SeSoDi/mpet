<script setup lang="ts">
import { computed } from 'vue'
import { Card, CardContent } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Label } from '@/components/ui/label'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { ChevronLeft, ChevronRight, Calendar, Building2 } from 'lucide-vue-next'

// Props interface
interface Props {
  year: number
  month: number
  monthName: string
  // Optional unit selector
  showUnits?: boolean
  selectedUnit?: string
  units?: { nombre: string; descripcion?: string }[]
  // Optional "current month" button
  showCurrentMonth?: boolean
  // Optional title
  title?: string
}

const props = withDefaults(defineProps<Props>(), {
  showUnits: false,
  selectedUnit: '',
  units: () => [],
  showCurrentMonth: false,
  title: '',
})

// Emits
const emit = defineEmits<{
  'navigate-prev': []
  'navigate-next': []
  'go-to-current': []
  'unit-changed': [unit: string]
}>()

// Methods
const goToPrevious = () => {
  emit('navigate-prev')
}

const goToNext = () => {
  emit('navigate-next')
}

const goToCurrent = () => {
  emit('go-to-current')
}

const changeUnit = (unit: any) => {
  if (unit && typeof unit === 'string') {
    emit('unit-changed', unit)
  }
}
</script>

<template>
  <Card class="mb-4 py-0">
    <CardContent class="px-6 py-3">
      <div v-if="props.title" class="flex items-center justify-between mb-3">
        <h2 class="text-sm font-medium text-gray-700">{{ props.title }}</h2>
      </div>

      <div class="flex items-center justify-center gap-8">
        <!-- Navegación por mes -->
        <div class="flex items-center gap-3">
          <Button
            variant="outline"
            size="sm"
            @click="goToPrevious"
            class="flex items-center gap-1 h-8"
          >
            <ChevronLeft class="h-3 w-3" />
            Anterior
          </Button>
          
          <div class="text-center min-w-28">
            <div class="font-medium text-sm">{{ props.monthName }} {{ props.year }}</div>
          </div>
          
          <Button
            variant="outline"
            size="sm"
            @click="goToNext"
            class="flex items-center gap-1 h-8"
          >
            Siguiente
            <ChevronRight class="h-3 w-3" />
          </Button>
        </div>

        <!-- Botón "Mes actual" (opcional) -->
        <div v-if="props.showCurrentMonth">
          <Button 
            variant="ghost" 
            size="sm"
            @click="goToCurrent"
            class="flex items-center gap-1 h-8"
          >
            <Calendar class="h-3 w-3" />
            Mes actual
          </Button>
        </div>

        <!-- Selector de unidad (opcional) -->
        <div v-if="props.showUnits" class="flex items-center gap-2">
          <Label for="unit-select" class="text-xs font-medium text-gray-600 whitespace-nowrap">Unidad:</Label>
          <Select :model-value="props.selectedUnit" @update:model-value="changeUnit">
            <SelectTrigger class="w-36 h-8 text-sm">
              <SelectValue placeholder="Seleccionar" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem 
                v-for="unit in props.units" 
                :key="unit.nombre" 
                :value="unit.nombre"
              >
                <div class="flex items-center gap-2">
                  <Building2 class="h-3 w-3" />
                  <span class="text-sm">{{ unit.nombre }}</span>
                </div>
              </SelectItem>
            </SelectContent>
          </Select>
        </div>
      </div>
    </CardContent>
  </Card>
</template>