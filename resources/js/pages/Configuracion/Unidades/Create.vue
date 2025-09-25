<script setup lang="ts">
import { computed, reactive, onMounted } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Badge } from '@/components/ui/badge'
import { Textarea } from '@/components/ui/textarea'
import { Switch } from '@/components/ui/switch'
import { ArrowLeft, Save, Edit3, Plus } from 'lucide-vue-next'
import { type BreadcrumbItem } from '@/types'
import { useUnsavedChanges } from '@/composables/useUnsavedChanges'

// Props del controlador
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

const props = withDefaults(defineProps<Props>(), {
  unidad: null,
})

// Computed properties
const isEditing = computed(() => props.unidad !== null)
const pageTitle = computed(() => isEditing.value ? 'Editar Unidad' : 'Nueva Unidad')
const submitButtonText = computed(() => isEditing.value ? 'Actualizar Unidad' : 'Crear Unidad')

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Configuración', href: '#' },
  { title: 'Unidades', href: '/units' },
  { title: pageTitle.value, href: '#' },
]

// Datos originales para detectar cambios - inicializar con los mismos valores que el form
const originalData = reactive({
  nombre: props.unidad?.nombre || '',
  descripcion: props.unidad?.descripcion || '',
  activo: props.unidad?.activo ?? true,
})

// Formulario con Inertia
const form = useForm({
  nombre: props.unidad?.nombre || '',
  descripcion: props.unidad?.descripcion || '',
  activo: props.unidad?.activo ?? true,
})

// Protección de cambios no guardados - comparar solo los campos de datos
const { hasUnsavedChanges, withoutProtection, resetOriginalData } = useUnsavedChanges(originalData, 
  // En lugar de pasar todo el form, crear un objeto computed con solo los campos que nos interesan
  computed(() => ({
    nombre: form.nombre,
    descripcion: form.descripcion,
    activo: form.activo,
  })), 
  {
    exclude: []  // No necesitamos excluir nada porque solo comparamos los campos específicos
  }
)

// Sincronizar datos originales cuando el componente se monta (después de que las props estén disponibles)
onMounted(() => {
  console.log('=== DEBUGGING UNSAVED CHANGES ===')
  console.log('Props unidad:', props.unidad)
  console.log('Form data:', { nombre: form.nombre, descripcion: form.descripcion, activo: form.activo })
  console.log('Original data before reset:', { ...originalData })
  
  // Resetear los datos originales para que coincidan exactamente con el form actual
  resetOriginalData({
    nombre: form.nombre,
    descripcion: form.descripcion,
    activo: form.activo,
  })
  
  console.log('Original data after reset:', { ...originalData })
  console.log('HasUnsavedChanges after reset:', hasUnsavedChanges.value)
  console.log('=== END DEBUGGING ===')
})

// Métodos
const saveUnidad = async () => {
  await withoutProtection(async () => {
    if (isEditing.value && props.unidad) {
      // Actualizar unidad existente
      form.put(`/units/${props.unidad.id}`, {
        onSuccess: () => {
          resetOriginalData(form.data())
        }
      })
    } else {
      // Crear nueva unidad
      form.post('/units', {
        onSuccess: () => {
          resetOriginalData(form.data())
        }
      })
    }
  })
}

// Formatear fecha
const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('es-MX', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>

<template>
  <Head :title="pageTitle" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="container max-w-4xl mx-auto py-8">
      <!-- Botón de regreso -->
      <div class="mb-6">
        <Button 
          as="Link" 
          :href="'/units'" 
          variant="ghost" 
          class="flex items-center gap-2"
        >
          <ArrowLeft class="h-4 w-4" />
          Volver a Unidades
        </Button>
      </div>

      <Card>
        <CardHeader>
          <CardTitle class="flex items-center gap-3">
            <div class="p-2 rounded-lg bg-blue-100">
              <component :is="isEditing ? Edit3 : Plus" class="h-6 w-6 text-blue-600" />
            </div>
            <div>
              <h1 class="text-2xl font-bold">{{ pageTitle }}</h1>
              <p class="text-sm text-gray-600 mt-1">
                {{ isEditing ? 'Modifica los datos de la unidad de negocio' : 'Crea una nueva unidad de negocio' }}
              </p>
            </div>
          </CardTitle>

          <!-- Información de estado si está editando -->
          <div v-if="isEditing && props.unidad" class="flex items-center gap-4 pt-4 border-t">
            <div class="flex items-center gap-2">
              <Badge :variant="props.unidad.activo ? 'default' : 'destructive'">
                {{ props.unidad.activo ? 'Activa' : 'Inactiva' }}
              </Badge>
            </div>
            
            <div class="text-sm text-gray-600">
              <span class="font-medium">Creada:</span>
              {{ formatDate(props.unidad.created_at) }}
            </div>
            
            <div class="text-sm text-gray-600">
              <span class="font-medium">Actualizada:</span>
              {{ formatDate(props.unidad.updated_at) }}
            </div>
          </div>
        </CardHeader>

        <form @submit.prevent="saveUnidad">
          <CardContent class="space-y-6">
            <!-- Nombre de la unidad -->
            <div class="space-y-2">
              <Label for="nombre" class="text-sm font-medium">
                Nombre de la Unidad *
              </Label>
              <Input
                id="nombre"
                v-model="form.nombre"
                type="text"
                placeholder="ej. Pachuca, Querétaro, Tulancingo..."
                :class="{ 'border-red-500': form.errors.nombre }"
                required
              />
              <p v-if="form.errors.nombre" class="text-sm text-red-600">
                {{ form.errors.nombre }}
              </p>
              <p class="text-sm text-gray-500">
                Nombre descriptivo de la unidad de negocio o sucursal
              </p>
            </div>

            <!-- Descripción -->
            <div class="space-y-2">
              <Label for="descripcion" class="text-sm font-medium">
                Descripción (Opcional)
              </Label>
              <Textarea
                id="descripcion"
                v-model="form.descripcion"
                placeholder="Descripción adicional de la unidad, región geográfica, características especiales..."
                :class="{ 'border-red-500': form.errors.descripcion }"
                rows="3"
              />
              <p v-if="form.errors.descripcion" class="text-sm text-red-600">
                {{ form.errors.descripcion }}
              </p>
              <p class="text-sm text-gray-500">
                Información adicional sobre la unidad (opcional)
              </p>
            </div>

            <!-- Estado activo/inactivo -->
            <div class="space-y-3">
              <Label class="text-sm font-medium">
                Estado de la Unidad
              </Label>
              <div class="flex items-center space-x-3">
                <Switch
                  id="activo"
                  v-model:checked="form.activo"
                  :class="{ 'border-red-500': form.errors.activo }"
                />
                <Label for="activo" class="text-sm">
                  <span class="font-medium" :class="form.activo ? 'text-green-600' : 'text-gray-600'">
                    {{ form.activo ? 'Unidad Activa' : 'Unidad Inactiva' }}
                  </span>
                </Label>
              </div>
              <p v-if="form.errors.activo" class="text-sm text-red-600">
                {{ form.errors.activo }}
              </p>
              <p class="text-sm text-gray-500">
                Las unidades inactivas no aparecerán en los formularios de captura
              </p>
            </div>
          </CardContent>

          <CardFooter class="flex items-center justify-between bg-gray-50">
            <div class="text-sm text-gray-600">
              <span v-if="hasUnsavedChanges" class="text-orange-600 font-medium">
                ⚠️ Tienes cambios sin guardar
              </span>
            </div>

            <div class="flex items-center gap-3">
              <Button 
                type="button"
                variant="ghost"
                as="Link"
                :href="'/units'"
              >
                Cancelar
              </Button>
              
              <Button 
                type="submit" 
                :disabled="form.processing"
                class="flex items-center gap-2"
              >
                <Save class="h-4 w-4" />
                {{ form.processing ? 'Guardando...' : submitButtonText }}
              </Button>
            </div>
          </CardFooter>
        </form>
      </Card>
    </div>
  </AppLayout>
</template>