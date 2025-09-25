<script setup lang="ts">
import { computed, reactive, ref } from 'vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import PeriodNavigation from '@/components/PeriodNavigation.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Badge } from '@/components/ui/badge'
import { ChevronLeft, ChevronRight, Calendar, Edit3, Save, X, Receipt, Building2 } from 'lucide-vue-next'
import { type BreadcrumbItem } from '@/types'
import { useUnsavedChanges } from '@/composables/useUnsavedChanges'
import CurrencyInput from '@/components/ui/currency-input.vue'

// Props del controlador
interface Props {
  facturacionActual?: {
    id: number
    unidad: string
    facturado_total_mxn: number
    consumo_total_m2: number
    facturas_emitidas: number
    facturas_canceladas: number
    facturas_contado: number
    facturacion_efectivo?: number
    periodo_inicio: string
    cal_anio: number
    cal_mes: number
    cal_yyyymm: string
    updated_at: string
  } | null
  unidadesActivas: {
    nombre: string
    descripcion?: string
  }[]
  anioActual: number
  mesActual: number
  unidadActual: string
  forceEdit: boolean
}

const props = withDefaults(defineProps<Props>(), {
  facturacionActual: null,
  anioActual: () => new Date().getFullYear(),
  mesActual: () => new Date().getMonth() + 1,
  unidadActual: 'Pachuca',
  forceEdit: false,
})

// Estado del componente
const isEditing = ref(!props.facturacionActual || props.forceEdit)
const selectedYear = ref(props.anioActual)
const selectedMonth = ref(props.mesActual)
const selectedUnidad = ref(props.unidadActual)

// Nombres de meses
const monthNames = [
  'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
  'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
]

// Datos originales para detección de cambios
const originalData = reactive({
  facturado_total_mxn: props.facturacionActual?.facturado_total_mxn || 0,
  consumo_total_m2: props.facturacionActual?.consumo_total_m2 || 0,
  facturas_emitidas: props.facturacionActual?.facturas_emitidas || 0,
  facturas_canceladas: props.facturacionActual?.facturas_canceladas || 0,
  facturas_contado: props.facturacionActual?.facturas_contado || 0,
  facturacion_efectivo: props.facturacionActual?.facturacion_efectivo || 0,
})

// Formulario con Inertia
const form = useForm({
  year: selectedYear.value,
  month: selectedMonth.value,
  unidad: selectedUnidad.value,
  facturado_total_mxn: props.facturacionActual?.facturado_total_mxn || 0,
  consumo_total_m2: props.facturacionActual?.consumo_total_m2 || 0,
  facturas_emitidas: props.facturacionActual?.facturas_emitidas || 0,
  facturas_canceladas: props.facturacionActual?.facturas_canceladas || 0,
  facturas_contado: props.facturacionActual?.facturas_contado || 0,
  facturacion_efectivo: props.facturacionActual?.facturacion_efectivo || 0,
})

// Protección de cambios no guardados - comparar solo los campos específicos
const { hasUnsavedChanges, withoutProtection, resetOriginalData } = useUnsavedChanges(originalData, 
  computed(() => ({
    facturado_total_mxn: form.facturado_total_mxn,
    consumo_total_m2: form.consumo_total_m2,
    facturas_emitidas: form.facturas_emitidas,
    facturas_canceladas: form.facturas_canceladas,
    facturas_contado: form.facturas_contado,
    facturacion_efectivo: form.facturacion_efectivo,
  }))
)

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Capturas', href: '#' },
  { title: 'Facturación', href: '/monthly-billing' },
]

// Unidad seleccionada 
const unidadSeleccionadaNombre = computed(() => {
  const unidad = props.unidadesActivas.find(u => u.nombre === selectedUnidad.value)
  return unidad?.nombre || selectedUnidad.value
})

// Badge de estado
const statusBadge = computed(() => {
  if (hasUnsavedChanges.value) {
    return {
      text: 'Cambios sin guardar',
      class: 'bg-yellow-100 text-yellow-800'
    }
  }
  
  if (props.facturacionActual) {
    return {
      text: 'Registro existente',
      class: 'bg-green-100 text-green-800'
    }
  }
  
  return {
    text: 'Nuevo registro',
    class: 'bg-blue-100 text-blue-800'
  }
})

// Formatear moneda
const formatCurrency = (value: number) => {
  return new Intl.NumberFormat('es-MX', {
    style: 'currency',
    currency: 'MXN'
  }).format(value)
}

// Obtener nombre del mes
const getMonthName = (month: number) => {
  return monthNames[month - 1] || month.toString()
}

// Navegación de períodos
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

  withoutProtection(() => {
    router.get('/monthly-billing', {
      year: newYear,
      month: newMonth,
      unidad: selectedUnidad.value
    })
  })
}

// Ir al mes actual
const goToCurrentMonth = () => {
  const now = new Date()
  withoutProtection(() => {
    router.get('/monthly-billing', {
      year: now.getFullYear(),
      month: now.getMonth() + 1,
      unidad: selectedUnidad.value
    })
  })
}

// Cambiar unidad
const changeUnidad = (nuevaUnidad: any) => {
  if (nuevaUnidad && typeof nuevaUnidad === 'string') {
    withoutProtection(() => {
      router.get('/monthly-billing', {
        year: selectedYear.value,
        month: selectedMonth.value,
        unidad: nuevaUnidad
      })
    })
  }
}

// Actualizar datos cuando cambie el registro existente
const updateFormFromProps = () => {
  if (props.facturacionActual) {
    form.facturado_total_mxn = props.facturacionActual.facturado_total_mxn || 0
    form.consumo_total_m2 = props.facturacionActual.consumo_total_m2 || 0
    form.facturas_emitidas = props.facturacionActual.facturas_emitidas || 0
    form.facturas_canceladas = props.facturacionActual.facturas_canceladas || 0
    form.facturas_contado = props.facturacionActual.facturas_contado || 0
    form.facturacion_efectivo = props.facturacionActual.facturacion_efectivo || 0
    
    // Actualizar datos originales también
    resetOriginalData({
      facturado_total_mxn: form.facturado_total_mxn,
      consumo_total_m2: form.consumo_total_m2,
      facturas_emitidas: form.facturas_emitidas,
      facturas_canceladas: form.facturas_canceladas,
      facturas_contado: form.facturas_contado,
      facturacion_efectivo: form.facturacion_efectivo,
    })
  }
}

// Guardar datos
const saveData = () => {
  // Actualizar form con valores actuales
  form.year = selectedYear.value
  form.month = selectedMonth.value
  form.unidad = selectedUnidad.value

  if (props.facturacionActual) {
    // Actualizar registro existente
    form.put(`/monthly-billing/${props.facturacionActual.id}`, {
      onSuccess: () => {
        isEditing.value = false
        resetOriginalData({
          facturado_total_mxn: form.facturado_total_mxn,
          consumo_total_m2: form.consumo_total_m2,
          facturas_emitidas: form.facturas_emitidas,
          facturas_canceladas: form.facturas_canceladas,
          facturas_contado: form.facturas_contado,
          facturacion_efectivo: form.facturacion_efectivo,
        })
      }
    })
  } else {
    // Crear nuevo registro
    form.post('/monthly-billing', {
      onSuccess: () => {
        isEditing.value = false
        resetOriginalData({
          facturado_total_mxn: form.facturado_total_mxn,
          consumo_total_m2: form.consumo_total_m2,
          facturas_emitidas: form.facturas_emitidas,
          facturas_canceladas: form.facturas_canceladas,
          facturas_contado: form.facturas_contado,
          facturacion_efectivo: form.facturacion_efectivo,
        })
      }
    })
  }
}

// Cancelar edición
const cancelEdit = () => {
  updateFormFromProps()
  isEditing.value = false
}

// Alternar modo edición
const toggleEdit = () => {
  isEditing.value = !isEditing.value
}
</script>

<template>
  <Head title="Captura - Facturación Mensual" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="container max-w-4xl mx-auto py-8">
      <!-- Título y estado -->
      <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
          <h1 class="text-2xl font-bold flex items-center gap-2">
            <Receipt class="h-6 w-6" />
            Facturación Mensual
          </h1>
          <Badge :class="statusBadge.class">
            {{ statusBadge.text }}
          </Badge>
        </div>
        
        <div class="flex items-center gap-2">
          <Link 
            href="/monthly-billing/list"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <Calendar class="h-4 w-4" />
            Ver Datos Brutos
          </Link>
        </div>
      </div>

      <!-- Controles de navegación -->
      <PeriodNavigation
        :year="selectedYear"
        :month="selectedMonth"
        :month-name="getMonthName(selectedMonth)"
        :show-units="true"
        :show-current-month="true"
        :selected-unit="selectedUnidad"
        :units="props.unidadesActivas"
        @navigate-prev="navigateMonth('prev')"
        @navigate-next="navigateMonth('next')"
        @go-to-current="goToCurrentMonth"
        @unit-changed="changeUnidad"
      />

      <!-- Formulario de captura -->
      <Card>
        <CardHeader class="flex flex-row items-center justify-between">
          <CardTitle>
            Datos de Facturación - {{ getMonthName(selectedMonth) }} {{ selectedYear }}
          </CardTitle>
          
          <div v-if="props.facturacionActual && !isEditing" class="flex items-center gap-2">
            <Button variant="outline" size="sm" @click="toggleEdit" class="flex items-center gap-2">
              <Edit3 class="h-4 w-4" />
              Editar
            </Button>
          </div>
        </CardHeader>
        
        <CardContent class="space-y-6">
          <!-- Campos del formulario -->
          <div class="grid gap-6 md:grid-cols-2">
            <!-- Total Facturado -->
            <div class="space-y-2">
              <Label for="facturado_total_mxn">Total Facturado (MXN)</Label>
              <CurrencyInput
                id="facturado_total_mxn"
                v-model="form.facturado_total_mxn"
                :disabled="!isEditing"
                placeholder="Ingrese el total facturado"
                :class="form.errors.facturado_total_mxn ? 'border-red-500' : ''"
              />
              <p v-if="form.errors.facturado_total_mxn" class="text-sm text-red-600">
                {{ form.errors.facturado_total_mxn }}
              </p>
            </div>

            <!-- Consumo Total -->
            <div class="space-y-2">
              <Label for="consumo_total_m2">Consumo Total (m³)</Label>
              <Input
                id="consumo_total_m2"
                type="number"
                step="0.01"
                min="0"
                :disabled="!isEditing"
                v-model.number="form.consumo_total_m2"
                placeholder="0.00"
                :class="{ 'border-red-500': form.errors.consumo_total_m2 }"
              />
              <p v-if="form.errors.consumo_total_m2" class="text-sm text-red-600">
                {{ form.errors.consumo_total_m2 }}
              </p>
            </div>

            <!-- Facturas Emitidas -->
            <div class="space-y-2">
              <Label for="facturas_emitidas">Facturas Emitidas</Label>
              <Input
                id="facturas_emitidas"
                type="number"
                min="0"
                :disabled="!isEditing"
                v-model.number="form.facturas_emitidas"
                placeholder="0"
                :class="{ 'border-red-500': form.errors.facturas_emitidas }"
              />
              <p v-if="form.errors.facturas_emitidas" class="text-sm text-red-600">
                {{ form.errors.facturas_emitidas }}
              </p>
            </div>

            <!-- Facturas Canceladas -->
            <div class="space-y-2">
              <Label for="facturas_canceladas">Facturas Canceladas</Label>
              <Input
                id="facturas_canceladas"
                type="number"
                min="0"
                :disabled="!isEditing"
                v-model.number="form.facturas_canceladas"
                placeholder="0"
                :class="{ 'border-red-500': form.errors.facturas_canceladas }"
              />
              <p v-if="form.errors.facturas_canceladas" class="text-sm text-red-600">
                {{ form.errors.facturas_canceladas }}
              </p>
            </div>

            <!-- Facturas de Contado -->
            <div class="space-y-2">
              <Label for="facturas_contado">Facturas de Contado</Label>
              <Input
                id="facturas_contado"
                type="number"
                min="0"
                :disabled="!isEditing"
                v-model.number="form.facturas_contado"
                placeholder="0"
                :class="{ 'border-red-500': form.errors.facturas_contado }"
              />
              <p v-if="form.errors.facturas_contado" class="text-sm text-red-600">
                {{ form.errors.facturas_contado }}
              </p>
            </div>

            <!-- Facturación Efectivo -->
            <div class="space-y-2">
              <Label for="facturacion_efectivo">Facturación Efectivo (MXN)</Label>
              <CurrencyInput
                id="facturacion_efectivo"
                v-model="form.facturacion_efectivo"
                :disabled="!isEditing"
                placeholder="Ingrese la facturación en efectivo"
                :class="form.errors.facturacion_efectivo ? 'border-red-500' : ''"
              />
              <p v-if="form.errors.facturacion_efectivo" class="text-sm text-red-600">
                {{ form.errors.facturacion_efectivo }}
              </p>
            </div>
          </div>

          <!-- Resumen -->
          <div v-if="form.facturado_total_mxn > 0" class="bg-green-50 p-4 rounded-lg">
            <div class="text-sm font-medium text-green-800">Total Facturado:</div>
            <div class="text-2xl font-bold text-green-600">
              {{ formatCurrency(form.facturado_total_mxn) }}
            </div>
          </div>

          <!-- Botones de acción -->
          <div v-if="isEditing" class="flex items-center gap-2 pt-4 border-t">
            <Button 
              @click="saveData" 
              :disabled="form.processing"
              class="flex items-center gap-2"
            >
              <Save class="h-4 w-4" />
              {{ form.processing ? 'Guardando...' : 'Guardar' }}
            </Button>
            
            <Button 
              variant="outline" 
              @click="cancelEdit"
              :disabled="form.processing"
              class="flex items-center gap-2"
            >
              <X class="h-4 w-4" />
              Cancelar
            </Button>
            
            <div v-if="hasUnsavedChanges" class="text-sm text-amber-600 ml-4 flex items-center gap-1">
              ⚠️ Hay cambios sin guardar
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>