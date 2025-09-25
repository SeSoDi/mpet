<script setup lang="ts">
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { Edit3, Plus, FileText, Receipt, Building2, Calendar } from 'lucide-vue-next'
import { type BreadcrumbItem } from '@/types'

// Props del controlador
interface Props {
  facturacion: {
    data: {
      id: number
      unidad: number
      unidad_model: {
        nombre: string
      } | null
      facturado_total_mxn: string | number  // Puede llegar como string o number
      cal_anio: number
      cal_mes: number
      cal_yyyymm: string
      updated_at: string
    }[]
    // Metadatos de paginación de Laravel
    current_page: number
    first_page_url: string
    from: number
    last_page: number
    last_page_url: string
    next_page_url: string | null
    path: string
    per_page: number
    prev_page_url: string | null
    to: number
    total: number
  }
  todosLosRegistros: {
    id: number
    unidad: number
    unidad_model: {
      nombre: string
    } | null
    facturado_total_mxn: string | number  // Puede llegar como string o number
    cal_anio: number
    cal_mes: number
    cal_yyyymm: string
    updated_at: string
  }[]
}

const props = defineProps<Props>()

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Datos Brutos', href: '#' },
  { title: 'Facturación', href: '/monthly-billing/list' },
]

// Nombres de meses
const monthNames = [
  'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
  'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
]

// Formatear moneda
const formatCurrency = (value: number | string) => {
  const numValue = typeof value === 'string' ? parseFloat(value) : value
  return new Intl.NumberFormat('es-MX', {
    style: 'currency',
    currency: 'MXN'
  }).format(numValue)
}

// Obtener nombre del mes
const getMonthName = (month: number) => {
  return monthNames[month - 1] || month.toString()
}

// Estadísticas computadas usando TODOS los registros (no solo los de la página actual)
const stats = computed(() => {
  const todosRegistros = props.todosLosRegistros
  
  const totalRegistros = todosRegistros?.length || 0
  const totalFacturado = todosRegistros?.reduce((sum, f) => {
    const valor = parseFloat(String(f.facturado_total_mxn)) || 0
    return sum + valor
  }, 0) || 0
  
  const unidadesUnicas = [...new Set(todosRegistros?.map(f => f.unidad_model?.nombre || f.unidad.toString()) || [])].length
  const promedioMensual = totalRegistros > 0 ? totalFacturado / totalRegistros : 0
  
  return {
    totalRegistros,
    totalFacturado,
    unidadesUnicas,
    promedioMensual
  }
})

// Agrupar por año para mejor organización (usando datos de la página actual)
const facturacionPorAnio = computed(() => {
  const agrupada: Record<number, typeof props.facturacion.data> = {}
  
  props.facturacion.data.forEach(item => {
    if (!agrupada[item.cal_anio]) {
      agrupada[item.cal_anio] = []
    }
    agrupada[item.cal_anio].push(item)
  })
  
  // Ordenar por año descendente
  const aniosOrdenados = Object.keys(agrupada)
    .map(Number)
    .sort((a, b) => b - a)
  
  return aniosOrdenados.map(anio => ({
    anio,
    registros: agrupada[anio].sort((a, b) => {
      // Ordenar por mes descendente, luego por unidad
      if (a.cal_mes !== b.cal_mes) {
        return b.cal_mes - a.cal_mes
      }
      const aNombre = a.unidad_model?.nombre || a.unidad.toString()
      const bNombre = b.unidad_model?.nombre || b.unidad.toString()
      return aNombre.localeCompare(bNombre)
    })
  }))
})
</script>

<template>
  <Head title="Datos Brutos - Facturación" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="container max-w-7xl mx-auto py-8">
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold flex items-center gap-2">
            <Receipt class="h-6 w-6" />
            Datos Brutos - Facturación
          </h1>
          <p class="text-gray-600 mt-1">Vista completa de todos los registros de facturación mensual por unidad</p>
        </div>
        
        <Button as="Link" :href="'/monthly-billing'" class="flex items-center gap-2">
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
            <CardTitle class="text-sm font-medium text-gray-600">Total Facturado</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold text-green-600">{{ formatCurrency(stats.totalFacturado.toString()) }}</div>
          </CardContent>
        </Card>
        
        <Card>
          <CardHeader class="pb-2">
            <CardTitle class="text-sm font-medium text-gray-600">Unidades Activas</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ stats.unidadesUnicas }}</div>
          </CardContent>
        </Card>
        
        <Card>
          <CardHeader class="pb-2">
            <CardTitle class="text-sm font-medium text-gray-600">Promedio Mensual</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ formatCurrency(stats.promedioMensual.toString()) }}</div>
          </CardContent>
        </Card>
      </div>

      <!-- Datos por año -->
      <div v-if="props.facturacion.data.length === 0" class="text-center py-12">
        <FileText class="h-12 w-12 text-gray-400 mx-auto mb-4" />
        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay datos disponibles</h3>
        <p class="text-gray-600 mb-4">Aún no se han creado registros de facturación mensual.</p>
        <Button as="Link" :href="'/monthly-billing'">
          Crear primer registro
        </Button>
      </div>

      <div v-else class="space-y-6">
        <Card v-for="{ anio, registros } in facturacionPorAnio" :key="anio">
          <CardHeader>
            <CardTitle class="flex items-center gap-2">
              <Calendar class="h-5 w-5" />
              Año {{ anio }}
              <Badge variant="outline">{{ registros.length }} registros</Badge>
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead>
                  <tr class="border-b">
                    <th class="text-left p-2 font-medium">Periodo</th>
                    <th class="text-left p-2 font-medium">Unidad</th>
                    <th class="text-left p-2 font-medium">Total Facturado</th>
                    <th class="text-left p-2 font-medium">Última Actualización</th>
                    <th class="text-left p-2 font-medium">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr 
                    v-for="(factura, index) in registros" 
                    :key="`${factura.cal_yyyymm}-${factura.unidad}`"
                    :class="[
                      'border-b hover:bg-blue-50 transition-colors duration-150',
                      index % 2 === 0 ? 'bg-white' : 'bg-gray-50'
                    ]"
                  >
                    <td class="p-2">
                      <div class="font-medium">
                        {{ getMonthName(factura.cal_mes) }} {{ factura.cal_anio }}
                      </div>
                    </td>
                    <td class="p-2">
                      <Badge variant="outline" class="flex items-center gap-1 w-fit">
                        <Building2 class="h-3 w-3" />
                        {{ factura.unidad_model?.nombre || factura.unidad }}
                      </Badge>
                    </td>
                    <td class="p-2">
                      <div class="font-medium text-green-600">{{ formatCurrency(parseFloat(String(factura.facturado_total_mxn))) }}</div>
                    </td>
                    <td class="p-2">
                      <div class="text-sm text-gray-600">
                        {{ new Date(factura.updated_at).toLocaleDateString('es-MX', { 
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
                        :href="`/monthly-billing?year=${factura.cal_anio}&month=${factura.cal_mes}&unidad=${factura.unidad}`"
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

      <!-- Pagination -->
      <div v-if="props.facturacion.last_page > 1" class="mt-6">
        <nav class="flex items-center justify-between">
          <div class="flex-1 flex justify-between sm:hidden">
            <Link
              v-if="props.facturacion.prev_page_url"
              :href="props.facturacion.prev_page_url"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            >
              Anterior
            </Link>
            <Link
              v-if="props.facturacion.next_page_url"
              :href="props.facturacion.next_page_url"
              class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            >
              Siguiente
            </Link>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700">
                Mostrando <span class="font-medium">{{ props.facturacion.from }}</span> a <span class="font-medium">{{ props.facturacion.to }}</span>
                de <span class="font-medium">{{ props.facturacion.total }}</span> resultados
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <Link
                  v-if="props.facturacion.prev_page_url"
                  :href="props.facturacion.prev_page_url"
                  class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                >
                  ‹
                </Link>
                <span
                  v-for="page in Math.min(props.facturacion.last_page, 10)"
                  :key="page"
                  :class="page === props.facturacion.current_page ? 'bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'"
                  class="relative inline-flex items-center px-4 py-2 border text-sm font-medium cursor-pointer"
                >
                  <Link :href="`${props.facturacion.path}?page=${page}`" class="block w-full h-full">
                    {{ page }}
                  </Link>
                </span>
                <Link
                  v-if="props.facturacion.next_page_url"
                  :href="props.facturacion.next_page_url"
                  class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                >
                  ›
                </Link>
              </nav>
            </div>
          </div>
        </nav>
      </div>
    </div>
  </AppLayout>
</template>