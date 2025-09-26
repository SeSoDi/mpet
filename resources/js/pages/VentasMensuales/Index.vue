<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { Head, useForm, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import PeriodNavigation from '@/components/PeriodNavigation.vue'
import { Card, CardContent, CardFooter } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Badge } from '@/components/ui/badge'
import { Edit3, Save, X } from 'lucide-vue-next'
import { useUnsavedChanges } from '@/composables/useUnsavedChanges'
import CurrencyInput from '@/components/ui/currency-input.vue'

// Props del controlador
interface Props {
  ventaActual?: {
    id: number
    periodo_inicio: string
    Prospectos: number
    VentasAproximadasMXN: string
    VentasCerradasMXN: string
    cal_anio: number
    cal_mes: number
    updated_at: string
  } | null
  anioActual: number
  mesActual: number
  forceEdit?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  ventaActual: null,
  anioActual: () => new Date().getFullYear(),
  mesActual: () => new Date().getMonth() + 1,
  forceEdit: false,
})

// Estado del componente
const isEditing = ref(!props.ventaActual || props.forceEdit) // Si no hay registro o forceEdit es true, empezamos editando
const selectedYear = ref(props.anioActual)
const selectedMonth = ref(props.mesActual)

// Watch forceEdit prop changes
watch(() => props.forceEdit, (newValue: boolean) => {
  if (newValue) {
    isEditing.value = true
  }
}, { immediate: true })

// Nombres de meses
const monthNames = [
  'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
  'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
]

// Formulario con Inertia
const form = useForm({
  year: selectedYear.value,
  month: selectedMonth.value,
  Prospectos: props.ventaActual?.Prospectos || 0,
  VentasAproximadasMXN: parseFloat(props.ventaActual?.VentasAproximadasMXN || '0'),
  VentasCerradasMXN: parseFloat(props.ventaActual?.VentasCerradasMXN || '0'),
})

// Original data for unsaved changes detection
const originalFormData = ref({
  Prospectos: props.ventaActual?.Prospectos || 0,
  VentasAproximadasMXN: parseFloat(props.ventaActual?.VentasAproximadasMXN || '0'),
  VentasCerradasMXN: parseFloat(props.ventaActual?.VentasCerradasMXN || '0'),
})

// Current form data as reactive computed
const currentFormData = computed(() => ({
  Prospectos: form.Prospectos,
  VentasAproximadasMXN: form.VentasAproximadasMXN,
  VentasCerradasMXN: form.VentasCerradasMXN,
}))

// Track unsaved changes
const { hasUnsavedChanges, withoutProtection, resetOriginalData } = useUnsavedChanges(
  originalFormData,
  currentFormData,
  {
    exclude: ['year', 'month'] // Don't track year/month changes as navigation
  }
)



// Computed properties
const currentMonthName = computed(() => monthNames[selectedMonth.value - 1])
const hasData = computed(() => props.ventaActual !== null)

const statusBadge = computed(() => {
  if (hasUnsavedChanges.value) {
    return {
      variant: 'destructive' as const,
      text: 'Cambios sin guardar',
      class: 'bg-yellow-100 text-yellow-800'
    }
  }
  
  if (hasData.value) {
    return {
      variant: 'default' as const,
      text: 'Datos guardados',
      class: 'bg-green-100 text-green-800'
    }
  }
  return {
    variant: 'secondary' as const, 
    text: 'Sin datos',
    class: 'bg-orange-100 text-orange-800'
  }
})

const lastUpdated = computed(() => {
  if (!props.ventaActual?.updated_at) return null
  
  const date = new Date(props.ventaActual.updated_at)
  return date.toLocaleDateString('es-MX', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
})

// Métodos
const navigateMonth = (direction: 'prev' | 'next') => {
  let newYear = selectedYear.value
  let newMonth = selectedMonth.value

  if (direction === 'prev') {
    newMonth--
    if (newMonth < 1) {
      newMonth = 12
      newYear--
    }
  } else {
    newMonth++
    if (newMonth > 12) {
      newMonth = 1
      newYear++
    }
  }

  selectedYear.value = newYear
  selectedMonth.value = newMonth
  navigateToMonth()
}

const goToCurrentMonth = () => {
  const now = new Date()
  selectedYear.value = now.getFullYear()
  selectedMonth.value = now.getMonth() + 1
  navigateToMonth()
}

const navigateToMonth = async () => {
  // If there are unsaved changes, let the user decide via the composable's confirmation
  if (hasUnsavedChanges.value) {
    // The composable will handle the confirmation dialog
    router.get('/monthly-sales', {
      year: selectedYear.value,
      month: selectedMonth.value
    }, {
      preserveState: false,
      preserveScroll: true
    })
  } else {
    // No unsaved changes, navigate freely
    await withoutProtection(async () => {
      router.get('/monthly-sales', {
        year: selectedYear.value,
        month: selectedMonth.value
      }, {
        preserveState: false,
        preserveScroll: true
      })
    })
  }
}

const startEditing = () => {
  isEditing.value = true
}

const cancelEditing = () => {
  // Restaurar valores originales
  form.reset()
  form.Prospectos = props.ventaActual?.Prospectos || 0
  form.VentasAproximadasMXN = parseFloat(props.ventaActual?.VentasAproximadasMXN || '0')
  form.VentasCerradasMXN = parseFloat(props.ventaActual?.VentasCerradasMXN || '0')
  
  // Solo salir del modo edición si ya hay datos
  if (hasData.value) {
    isEditing.value = false
  }
}

const saveData = async () => {
  form.year = selectedYear.value
  form.month = selectedMonth.value
  
  await withoutProtection(async () => {
    if (hasData.value) {
      // Actualizar registro existente
      form.put(`/monthly-sales/${props.ventaActual?.id}`, {
        onSuccess: () => {
          isEditing.value = false
          // Reset original data to current form values after successful save
          resetOriginalData({
            Prospectos: form.Prospectos,
            VentasAproximadasMXN: form.VentasAproximadasMXN,
            VentasCerradasMXN: form.VentasCerradasMXN,
          })
        }
      })
    } else {
      // Crear nuevo registro
      form.post('/monthly-sales', {
        onSuccess: () => {
          isEditing.value = false
          // Reset original data to current form values after successful save
          resetOriginalData({
            Prospectos: form.Prospectos,
            VentasAproximadasMXN: form.VentasAproximadasMXN,
            VentasCerradasMXN: form.VentasCerradasMXN,
          })
        }
      })
    }
  })
}
</script>

<template>
  <Head title="Captura de Ventas" />

  <AppLayout>
    <div class="container max-w-4xl mx-auto py-8">
      <!-- Título y estado -->
      <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
          <h1 class="text-2xl font-bold">📈 Captura de Ventas</h1>
          <Badge :class="statusBadge.class">
            {{ statusBadge.text }}
          </Badge>
        </div>
      </div>

      <!-- Controles de navegación -->
      <PeriodNavigation
        :year="selectedYear"
        :month="selectedMonth"
        :month-name="currentMonthName"
        :show-current-month="true"
        @navigate-prev="navigateMonth('prev')"
        @navigate-next="navigateMonth('next')"
        @go-to-current="goToCurrentMonth"
      />

      <Card>
        <CardContent class="space-y-6 pt-6">
          <!-- Campos del formulario -->
          <div class="grid gap-6">
            <!-- Prospectos -->
            <div class="grid gap-2">
              <Label for="prospectos" class="text-base font-medium">
                👥 Prospectos
              </Label>
              <Input
                id="prospectos"
                v-model.number="form.Prospectos"
                type="number"
                :disabled="!isEditing"
                :class="{ 'bg-gray-50': !isEditing }"
                min="0"
                step="1"
              />
            </div>

            <!-- Ventas Aproximadas -->
            <div class="grid gap-2">
              <Label for="ventas-aproximadas" class="text-base font-medium">
                💰 Ventas Aproximadas (MXN)
              </Label>

              <CurrencyInput
                id="ventas-aproximadas"
                v-model="form.VentasAproximadasMXN"
                :disabled="!isEditing"
                :class="!isEditing ? 'bg-gray-50' : ''"
                placeholder="Ingrese el monto aproximado"
              />
            </div>

            <!-- Ventas Cerradas -->
            <div class="grid gap-2">
              <Label for="ventas-cerradas" class="text-base font-medium">
                ✅ Ventas Cerradas (MXN)
              </Label>
              <CurrencyInput
                id="ventas-cerradas"
                v-model="form.VentasCerradasMXN"
                :disabled="!isEditing"
                :class="!isEditing ? 'bg-gray-50' : ''"
                placeholder="Ingrese el monto cerrado"
              />
            </div>
          </div>

          <!-- Información adicional -->
          <div v-if="lastUpdated" class="pt-4 border-t">
            <p class="text-sm text-gray-600">
              <strong>Última actualización:</strong> {{ lastUpdated }}
            </p>
          </div>
        </CardContent>

        <CardFooter class="flex justify-between">
          <div class="flex items-center gap-2 text-sm text-gray-600">
            <span v-if="form.processing">Guardando...</span>
          </div>
          
          <div class="flex gap-2">
            <template v-if="isEditing">
              <Button 
                variant="outline" 
                @click="cancelEditing"
                :disabled="form.processing"
              >
                <X class="h-4 w-4 mr-2" />
                Cancelar
              </Button>
              <Button 
                @click="saveData"
                :disabled="form.processing"
              >
                <Save class="h-4 w-4 mr-2" />
                Guardar
              </Button>
            </template>
            
            <template v-else>
              <Button 
                @click="startEditing"
                class="flex items-center gap-2"
              >
                <Edit3 class="h-4 w-4" />
                Editar
              </Button>
            </template>
          </div>
        </CardFooter>
      </Card>
    </div>
  </AppLayout>
</template>