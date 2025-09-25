<script setup lang="ts">
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { Edit3, Plus, Settings, MapPin } from 'lucide-vue-next'
import { type BreadcrumbItem } from '@/types'

// Props del controlador
interface Props {
  unidades: {
    data: {
      id: number
      nombre: string
      activo: boolean
      descripcion?: string
      created_at: string
      updated_at: string
    }[]
  }
}

const props = defineProps<Props>()

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Configuración', href: '#' },
  { title: 'Unidades', href: '/units' },
]

// Estadísticas computadas
const stats = computed(() => {
  const unidades = props.unidades.data
  const totalActivas = unidades.filter(u => u.activo).length
  const totalInactivas = unidades.filter(u => !u.activo).length
  
  return {
    totalUnidades: unidades.length,
    totalActivas,
    totalInactivas,
    porcentajeActivas: unidades.length > 0 ? ((totalActivas / unidades.length) * 100).toFixed(1) : '0'
  }
})
</script>

<template>
  <Head title="Configuración - Unidades" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="container max-w-7xl mx-auto py-8">
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold flex items-center gap-2">
            <Settings class="h-6 w-6" />
            Configuración - Unidades
          </h1>
          <p class="text-gray-600 mt-1">Gestión de unidades de negocio (sucursales y regiones geográficas)</p>
        </div>
        
        <Button as="Link" :href="'/units/create'" class="flex items-center gap-2">
          <Plus class="h-4 w-4" />
          Nueva Unidad
        </Button>
      </div>

      <!-- Estadísticas generales -->
      <div class="grid gap-4 md:grid-cols-4 mb-6">
        <Card>
          <CardHeader class="pb-2">
            <CardTitle class="text-sm font-medium text-gray-600">Total Unidades</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ stats.totalUnidades }}</div>
          </CardContent>
        </Card>
        
        <Card>
          <CardHeader class="pb-2">
            <CardTitle class="text-sm font-medium text-gray-600">Unidades Activas</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold text-green-600">{{ stats.totalActivas }}</div>
          </CardContent>
        </Card>
        
        <Card>
          <CardHeader class="pb-2">
            <CardTitle class="text-sm font-medium text-gray-600">Unidades Inactivas</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold text-red-600">{{ stats.totalInactivas }}</div>
          </CardContent>
        </Card>
        
        <Card>
          <CardHeader class="pb-2">
            <CardTitle class="text-sm font-medium text-gray-600">% Activas</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ stats.porcentajeActivas }}%</div>
          </CardContent>
        </Card>
      </div>

      <!-- Tabla de datos -->
      <Card>
        <CardHeader>
          <CardTitle>Unidades de Negocio</CardTitle>
        </CardHeader>
        <CardContent>
          <div v-if="props.unidades.data.length === 0" class="text-center py-12">
            <MapPin class="h-12 w-12 text-gray-400 mx-auto mb-4" />
            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay unidades configuradas</h3>
            <p class="text-gray-600 mb-4">Aún no se han creado unidades de negocio en el sistema.</p>
            <Button as="Link" :href="'/units/create'">
              Crear primera unidad
            </Button>
          </div>

          <div v-else class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b">
                  <th class="text-left p-2 font-medium">Nombre</th>
                  <th class="text-left p-2 font-medium">Descripción</th>
                  <th class="text-left p-2 font-medium">Estado</th>
                  <th class="text-left p-2 font-medium">Creada</th>
                  <th class="text-left p-2 font-medium">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr 
                  v-for="unidad in props.unidades.data" 
                  :key="unidad.id"
                  class="border-b hover:bg-gray-50"
                >
                  <td class="p-2">
                    <div class="font-medium flex items-center gap-2">
                      <MapPin class="h-4 w-4 text-gray-400" />
                      {{ unidad.nombre }}
                    </div>
                  </td>
                  <td class="p-2">
                    <div class="text-sm text-gray-600">
                      {{ unidad.descripcion || '-' }}
                    </div>
                  </td>
                  <td class="p-2">
                    <Badge :variant="unidad.activo ? 'default' : 'destructive'">
                      {{ unidad.activo ? 'Activa' : 'Inactiva' }}
                    </Badge>
                  </td>
                  <td class="p-2">
                    <div class="text-sm text-gray-600">
                      {{ new Date(unidad.created_at).toLocaleDateString('es-MX', { 
                        day: 'numeric', 
                        month: 'short', 
                        year: 'numeric'
                      }) }}
                    </div>
                  </td>
                  <td class="p-2">
                    <Link 
                      :href="`/units/${unidad.id}/edit`"
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