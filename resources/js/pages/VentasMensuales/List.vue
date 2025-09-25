<script setup lang="ts">
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { Edit3, Plus, FileText } from 'lucide-vue-next'
import { type BreadcrumbItem } from '@/types'

// Props del controlador
interface Props {
  ventas: {
    data: {
      id: number
      periodo_inicio: string
      Prospectos: number
      VentasAproximadasMXN: string
      VentasCerradasMXN: string
      cal_anio: number
      cal_mes: number
      updated_at: string
    }[]
  }
}

const props = defineProps<Props>()

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Datos Brutos', href: '#' },
  { title: 'Ventas', href: '/monthly-sales/list' },
]

// Nombres de meses
const monthNames = [
  'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
  'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
]

// Formatear moneda
const formatCurrency = (value: string) => {
  return new Intl.NumberFormat('es-MX', {
    style: 'currency',
    currency: 'MXN'
  }).format(parseFloat(value))
}

// Obtener nombre del mes
const getMonthName = (month: number) => {
  return monthNames[month - 1] || month.toString()
}

// Estadísticas computadas
const stats = computed(() => {
  const ventas = props.ventas.data
  const totalProspectos = ventas.reduce((sum, v) => sum + v.Prospectos, 0)
  const totalAproximadas = ventas.reduce((sum, v) => sum + parseFloat(v.VentasAproximadasMXN), 0)
  const totalCerradas = ventas.reduce((sum, v) => sum + parseFloat(v.VentasCerradasMXN), 0)
  
  return {
    totalRegistros: ventas.length,
    totalProspectos,
    totalAproximadas,
    totalCerradas,
    tasaConversion: totalProspectos > 0 ? ((totalCerradas / totalAproximadas) * 100).toFixed(1) : '0'
  }
})
</script>

<template>
  <Head title="Datos Brutos - Ventas" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="container max-w-7xl mx-auto py-8">
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold flex items-center gap-2">
            📈 Datos Brutos - Ventas
          </h1>
          <p class="text-gray-600 mt-1">Vista completa de todos los registros de ventas mensuales</p>
        </div>
        
        <Button as="Link" :href="'/monthly-sales'" class="flex items-center gap-2">
          <Plus class="h-4 w-4" />
          Nueva Captura
        </Button>
      </div>

      <!-- Estadísticas generales -->
      <div class="grid gap-4 md:grid-cols-4 mb-6">
        <Card>
          <CardHeader class="pb-2">
            <CardTitle class="text-sm font-medium text-gray-600">Total Registros</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ stats.totalRegistros }}</div>
          </CardContent>
        </Card>
        
        <Card>
          <CardHeader class="pb-2">
            <CardTitle class="text-sm font-medium text-gray-600">Total Prospectos</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ stats.totalProspectos.toLocaleString() }}</div>
          </CardContent>
        </Card>
        
        <Card>
          <CardHeader class="pb-2">
            <CardTitle class="text-sm font-medium text-gray-600">Ventas Cerradas</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ formatCurrency(stats.totalCerradas.toString()) }}</div>
          </CardContent>
        </Card>
        
        <Card>
          <CardHeader class="pb-2">
            <CardTitle class="text-sm font-medium text-gray-600">Tasa Conversión</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ stats.tasaConversion }}%</div>
          </CardContent>
        </Card>
      </div>

      <!-- Tabla de datos -->
      <Card>
        <CardHeader>
          <CardTitle>Registros de Ventas Mensuales</CardTitle>
        </CardHeader>
        <CardContent>
          <div v-if="props.ventas.data.length === 0" class="text-center py-12">
            <FileText class="h-12 w-12 text-gray-400 mx-auto mb-4" />
            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay datos disponibles</h3>
            <p class="text-gray-600 mb-4">Aún no se han creado registros de ventas mensuales.</p>
            <Button as="Link" :href="'/monthly-sales'">
              Crear primer registro
            </Button>
          </div>

          <div v-else class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b">
                  <th class="text-left p-2 font-medium">Periodo</th>
                  <th class="text-left p-2 font-medium">Prospectos</th>
                  <th class="text-left p-2 font-medium">Ventas Aproximadas</th>
                  <th class="text-left p-2 font-medium">Ventas Cerradas</th>
                  <th class="text-left p-2 font-medium">Última Actualización</th>
                  <th class="text-left p-2 font-medium">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr 
                  v-for="venta in props.ventas.data" 
                  :key="venta.id"
                  class="border-b hover:bg-gray-50"
                >
                  <td class="p-2">
                    <div class="font-medium">
                      {{ getMonthName(venta.cal_mes) }} {{ venta.cal_anio }}
                    </div>
                  </td>
                  <td class="p-2">
                    <Badge variant="outline">
                      👥 {{ venta.Prospectos.toLocaleString() }}
                    </Badge>
                  </td>
                  <td class="p-2">
                    <div class="font-medium">{{ formatCurrency(venta.VentasAproximadasMXN) }}</div>
                  </td>
                  <td class="p-2">
                    <div class="font-medium text-green-600">{{ formatCurrency(venta.VentasCerradasMXN) }}</div>
                  </td>
                  <td class="p-2">
                    <div class="text-sm text-gray-600">
                      {{ new Date(venta.updated_at).toLocaleDateString('es-MX', { 
                        day: 'numeric', 
                        month: 'short', 
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                      }) }}
                    </div>
                  </td>
                  <td class="p-2">
                    <Link 
                      :href="`/monthly-sales?year=${venta.cal_anio}&month=${venta.cal_mes}&edit=true`"
                      class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                      <Edit3 class="h-3 w-3" />
                      Editar
                    </Link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>