<template>
  <Head title="Detalles del Log" />
  
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Detalles del Log</h1>
            <div class="flex gap-2">
              <Link
                href="/logs"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
              >
                Volver a Bitácora
              </Link>
              <button
                v-if="hasPermission('delete_logs')"
                @click="deleteLog"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
              >
                Eliminar
              </button>
            </div>
          </div>

          <!-- Log Details Card -->
          <div class="bg-gray-50 rounded-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Basic Info -->
              <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Básica</h3>
                
                <div class="space-y-3">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">ID</label>
                    <div class="text-sm text-gray-900">{{ log.id }}</div>
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Fecha y Hora</label>
                    <div class="text-sm text-gray-900">{{ formatDate(log.logged_at) }}</div>
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Nivel</label>
                    <span :class="getLevelBadgeClass(log.level)" class="inline-flex px-2 py-1 text-xs font-medium rounded-full">
                      {{ log.level }}
                    </span>
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Canal</label>
                    <div class="text-sm text-gray-900">{{ getChannelDisplayName(log.channel) }}</div>
                  </div>
                </div>
              </div>

              <!-- User Info -->
              <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información del Usuario</h3>
                
                <div class="space-y-3">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Usuario</label>
                    <div class="text-sm text-gray-900">
                      {{ log.user?.name || log.user_name || 'Sistema' }}
                    </div>
                  </div>
                  
                  <div v-if="log.user?.email">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="text-sm text-gray-900">{{ log.user.email }}</div>
                  </div>
                  
                  <div v-if="log.user_id">
                    <label class="block text-sm font-medium text-gray-700">ID de Usuario</label>
                    <div class="text-sm text-gray-900">{{ log.user_id }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Message -->
          <div class="bg-white border rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Mensaje</h3>
            <div class="text-sm text-gray-900 whitespace-pre-wrap">{{ log.message }}</div>
          </div>

          <!-- Context -->
          <div v-if="log.context && Object.keys(log.context).length > 0" class="bg-white border rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Contexto</h3>
            
            <!-- Request Information -->
            <div v-if="hasRequestInfo" class="mb-6">
              <h4 class="text-md font-medium text-gray-800 mb-3">Información de la Petición</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div v-if="log.context.ip">
                  <label class="block font-medium text-gray-700">IP</label>
                  <div class="text-gray-900">{{ log.context.ip }}</div>
                </div>
                
                <div v-if="log.context.method">
                  <label class="block font-medium text-gray-700">Método HTTP</label>
                  <div class="text-gray-900">{{ log.context.method }}</div>
                </div>
                
                <div v-if="log.context.url" class="md:col-span-2">
                  <label class="block font-medium text-gray-700">URL</label>
                  <div class="text-gray-900 break-all">{{ log.context.url }}</div>
                </div>
                
                <div v-if="log.context.user_agent" class="md:col-span-2">
                  <label class="block font-medium text-gray-700">User Agent</label>
                  <div class="text-gray-900 break-all">{{ log.context.user_agent }}</div>
                </div>
              </div>
            </div>

            <!-- Action Details -->
            <div v-if="hasActionInfo" class="mb-6">
              <h4 class="text-md font-medium text-gray-800 mb-3">Detalles de la Acción</h4>
              <div class="text-sm space-y-2">
                <div v-if="log.context.action">
                  <label class="block font-medium text-gray-700">Acción</label>
                  <div class="text-gray-900">{{ log.context.action }}</div>
                </div>
                
                <div v-if="log.context.target_user_name">
                  <label class="block font-medium text-gray-700">Usuario Objetivo</label>
                  <div class="text-gray-900">{{ log.context.target_user_name }}</div>
                </div>
                
                <div v-if="log.context.changes">
                  <label class="block font-medium text-gray-700">Cambios Realizados</label>
                  <div class="bg-gray-100 p-3 rounded mt-1">
                    <pre class="text-xs text-gray-800 whitespace-pre-wrap">{{ JSON.stringify(log.context.changes, null, 2) }}</pre>
                  </div>
                </div>
              </div>
            </div>

            <!-- Error Information -->
            <div v-if="hasErrorInfo" class="mb-6">
              <h4 class="text-md font-medium text-red-800 mb-3">Información del Error</h4>
              <div class="text-sm space-y-2">
                <div v-if="log.context.exception">
                  <label class="block font-medium text-gray-700">Tipo de Excepción</label>
                  <div class="text-red-600 font-mono">{{ log.context.exception }}</div>
                </div>
                
                <div v-if="log.context.file">
                  <label class="block font-medium text-gray-700">Archivo</label>
                  <div class="text-gray-900 font-mono">{{ log.context.file }}:{{ log.context.line }}</div>
                </div>
                
                <div v-if="log.context.trace" class="mt-4">
                  <label class="block font-medium text-gray-700">Stack Trace</label>
                  <div class="bg-red-50 border border-red-200 rounded p-3 mt-1 max-h-64 overflow-y-auto">
                    <pre class="text-xs text-red-800 whitespace-pre-wrap">{{ log.context.trace }}</pre>
                  </div>
                </div>
              </div>
            </div>

            <!-- Full Context (Raw JSON) -->
            <div>
              <h4 class="text-md font-medium text-gray-800 mb-3">Contexto Completo (JSON)</h4>
              <div class="bg-gray-100 border rounded p-4 max-h-96 overflow-y-auto">
                <pre class="text-xs text-gray-800 whitespace-pre-wrap">{{ JSON.stringify(log.context, null, 2) }}</pre>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { Link, router, Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { index as logsIndex, show as logsShow } from '@/routes/logs';
import { usePermissions } from '@/composables/usePermissions';
import type { BreadcrumbItem } from '@/types';

interface Log {
  id: number;
  level: string;
  channel: string;
  message: string;
  context: any;
  user_id: number | null;
  user_name: string | null;
  logged_at: string;
  user?: {
    id: number;
    name: string;
    email: string;
  };
}

const props = defineProps<{
  log: Log;
}>();

const { hasPermission } = usePermissions();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Bitácora',
        href: logsIndex().url,
    },
    {
        title: `Log #${props.log.id}`,
        href: logsShow({ log: props.log.id }).url,
    },
];

const hasRequestInfo = computed(() => {
  return props.log.context && (
    props.log.context.ip ||
    props.log.context.method ||
    props.log.context.url ||
    props.log.context.user_agent
  );
});

const hasActionInfo = computed(() => {
  return props.log.context && (
    props.log.context.action ||
    props.log.context.target_user_name ||
    props.log.context.changes
  );
});

const hasErrorInfo = computed(() => {
  return props.log.context && (
    props.log.context.exception ||
    props.log.context.file ||
    props.log.context.trace
  );
});

const formatDate = (dateString: string): string => {
  return new Date(dateString).toLocaleString('es-ES', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    timeZoneName: 'short',
  });
};

const getLevelBadgeClass = (level: string): string => {
  const classes: Record<string, string> = {
    'DEBUG': 'bg-gray-100 text-gray-800',
    'INFO': 'bg-blue-100 text-blue-800',
    'WARNING': 'bg-yellow-100 text-yellow-800',
    'ERROR': 'bg-red-100 text-red-800',
    'CRITICAL': 'bg-red-500 text-white',
  };
  return classes[level] || 'bg-gray-100 text-gray-800';
};

const getChannelDisplayName = (channel: string): string => {
  const channelNames: Record<string, string> = {
    'auth': 'Autenticación',
    'user_management': 'Gestión de Usuarios',
    'role_permission': 'Roles y Permisos',
    'system': 'Sistema',
    'api': 'API',
    'test': 'Prueba',
  };
  return channelNames[channel] || channel;
};

const deleteLog = () => {
  if (confirm('¿Estás seguro de que quieres eliminar este log?')) {
    router.delete(`/logs/${props.log.id}`, {
      onSuccess: () => {
        router.visit('/logs');
      }
    });
  }
};
</script>