<template>
  <Head title="Bitácora" />
  
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Bitácora</h1>
            <div class="flex gap-2">
              <button 
                @click="refreshLogs"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
              >
                Actualizar
              </button>
              <button 
                v-if="hasPermission('delete_logs')"
                @click="showBulkDelete = true"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
              >
                Limpiar Logs
              </button>
            </div>
          </div>

          <!-- Filters -->
          <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
              <!-- Search -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Buscar en mensaje</label>
                <input
                  v-model="filters.search"
                  @input="debounceSearch"
                  type="text"
                  placeholder="Buscar..."
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                >
              </div>

              <!-- Level Filter -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nivel</label>
                <select
                  v-model="filters.level"
                  @change="applyFilters"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="">Todos los niveles</option>
                  <option v-for="level in availableFilters.levels" :key="level" :value="level">
                    {{ level }}
                  </option>
                </select>
              </div>

              <!-- Channel Filter -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Canal</label>
                <select
                  v-model="filters.channel"
                  @change="applyFilters"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="">Todos los canales</option>
                  <option v-for="channel in availableFilters.channels" :key="channel" :value="channel">
                    {{ getChannelDisplayName(channel) }}
                  </option>
                </select>
              </div>

              <!-- Date Range -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                <select
                  v-model="filters.dateRange"
                  @change="applyDateRange"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="">Todas las fechas</option>
                  <option value="today">Hoy</option>
                  <option value="yesterday">Ayer</option>
                  <option value="week">Esta semana</option>
                  <option value="month">Este mes</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Logs Table -->
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Fecha/Hora
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Nivel
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Canal
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Usuario
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Mensaje
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Acciones
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="log in logs.data" :key="log.id" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ formatDate(log.logged_at) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="getLevelBadgeClass(log.level)" class="px-2 py-1 text-xs font-medium rounded-full">
                      {{ log.level }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ getChannelDisplayName(log.channel) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ log.user?.name || log.user_name || '—' }}
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-900 max-w-md truncate">
                    {{ log.message }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <Link
                      :href="`/logs/${log.id}`"
                      class="text-blue-600 hover:text-blue-900 mr-2"
                    >
                      Ver
                    </Link>
                    <button
                      v-if="hasPermission('delete_logs')"
                      @click="deleteLog(log)"
                      class="text-red-600 hover:text-red-900"
                    >
                      Eliminar
                    </button>
                  </td>
                </tr>
                <tr v-if="logs.data.length === 0">
                  <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                    No se encontraron logs
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div v-if="logs.last_page > 1" class="mt-6">
            <nav class="flex items-center justify-between">
              <div class="flex-1 flex justify-between sm:hidden">
                <Link
                  v-if="logs.prev_page_url"
                  :href="logs.prev_page_url"
                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                >
                  Anterior
                </Link>
                <Link
                  v-if="logs.next_page_url"
                  :href="logs.next_page_url"
                  class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                >
                  Siguiente
                </Link>
              </div>
              <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                  <p class="text-sm text-gray-700">
                    Mostrando <span class="font-medium">{{ logs.from }}</span> a <span class="font-medium">{{ logs.to }}</span>
                    de <span class="font-medium">{{ logs.total }}</span> resultados
                  </p>
                </div>
                <div>
                  <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <Link
                      v-if="logs.prev_page_url"
                      :href="logs.prev_page_url"
                      class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                    >
                      ‹
                    </Link>
                    <span
                      v-for="page in pageNumbers"
                      :key="page"
                      :class="page === logs.current_page ? 'bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'"
                      class="relative inline-flex items-center px-4 py-2 border text-sm font-medium cursor-pointer"
                      @click="goToPage(page)"
                    >
                      {{ page }}
                    </span>
                    <Link
                      v-if="logs.next_page_url"
                      :href="logs.next_page_url"
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
      </div>
    </div>

    <!-- Bulk Delete Modal -->
    <div v-if="showBulkDelete" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Eliminar Logs</h3>
        <form @submit.prevent="submitBulkDelete">
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Eliminar logs anteriores a (días):
            </label>
            <input
              v-model="bulkDeleteForm.older_than_days"
              type="number"
              min="1"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-md"
            >
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Nivel (opcional):
            </label>
            <select v-model="bulkDeleteForm.level" class="w-full px-3 py-2 border border-gray-300 rounded-md">
              <option value="">Todos los niveles</option>
              <option v-for="level in availableFilters.levels" :key="level" :value="level">
                {{ level }}
              </option>
            </select>
          </div>
          <div class="flex justify-end gap-2">
            <button
              type="button"
              @click="showBulkDelete = false"
              class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700"
            >
              Cancelar
            </button>
            <button
              type="submit"
              class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700"
            >
              Eliminar
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted } from 'vue';
import { Link, router, Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { index as logsIndex } from '@/routes/logs';
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

interface LogsData {
  data: Log[];
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
  from: number;
  to: number;
  prev_page_url: string | null;
  next_page_url: string | null;
}

const props = defineProps<{
  logs: LogsData;
  filters: {
    levels: string[];
    channels: string[];
  };
  queryParams: Record<string, any>;
}>();

const { hasPermission } = usePermissions();

const showBulkDelete = ref(false);
const filters = reactive({
  search: props.queryParams.search || '',
  level: props.queryParams.level || '',
  channel: props.queryParams.channel || '',
  dateRange: '',
});

const bulkDeleteForm = reactive({
  older_than_days: 30,
  level: '',
  channel: '',
});

const availableFilters = computed(() => props.filters);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Bitácora',
        href: logsIndex().url,
    },
];

const pageNumbers = computed(() => {
  const pages = [];
  const start = Math.max(1, props.logs.current_page - 2);
  const end = Math.min(props.logs.last_page, props.logs.current_page + 2);
  
  for (let i = start; i <= end; i++) {
    pages.push(i);
  }
  return pages;
});

let searchTimeout: number | null = null;

const debounceSearch = () => {
  if (searchTimeout) clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    applyFilters();
  }, 500);
};

const applyFilters = () => {
  const params: Record<string, any> = {};
  if (filters.search) params.search = filters.search;
  if (filters.level) params.level = filters.level;
  if (filters.channel) params.channel = filters.channel;
  
  router.get('/logs', params, {
    preserveState: true,
    replace: true,
  });
};

const applyDateRange = () => {
  const today = new Date();
  let dateFrom = '';
  let dateTo = '';

  switch (filters.dateRange) {
    case 'today':
      dateFrom = today.toISOString().split('T')[0];
      break;
    case 'yesterday':
      const yesterday = new Date(today);
      yesterday.setDate(yesterday.getDate() - 1);
      dateFrom = yesterday.toISOString().split('T')[0];
      dateTo = yesterday.toISOString().split('T')[0];
      break;
    case 'week':
      const weekAgo = new Date(today);
      weekAgo.setDate(weekAgo.getDate() - 7);
      dateFrom = weekAgo.toISOString().split('T')[0];
      break;
    case 'month':
      const monthAgo = new Date(today);
      monthAgo.setMonth(monthAgo.getMonth() - 1);
      dateFrom = monthAgo.toISOString().split('T')[0];
      break;
  }

  const params: Record<string, any> = {
    ...Object.fromEntries(Object.entries(filters).filter(([key, value]) => value && key !== 'dateRange'))
  };
  
  if (dateFrom) params.date_from = dateFrom;
  if (dateTo) params.date_to = dateTo;
  
  router.get('/logs', params, {
    preserveState: true,
    replace: true,
  });
};

const refreshLogs = () => {
  router.reload();
};

const goToPage = (page: number) => {
  const params = { ...props.queryParams, page };
  router.get('/logs', params, {
    preserveState: true,
    replace: true,
  });
};

const deleteLog = (log: Log) => {
  if (confirm('¿Estás seguro de que quieres eliminar este log?')) {
    router.delete(`/logs/${log.id}`, {
      preserveState: true,
    });
  }
};

const submitBulkDelete = () => {
  if (confirm(`¿Estás seguro de que quieres eliminar logs anteriores a ${bulkDeleteForm.older_than_days} días?`)) {
    router.post('/logs/bulk-delete', bulkDeleteForm, {
      onSuccess: () => {
        showBulkDelete.value = false;
      }
    });
  }
};

const formatDate = (dateString: string): string => {
  return new Date(dateString).toLocaleString('es-ES', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
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

onMounted(() => {
  // Set initial date range filter based on query params
  if (props.queryParams.date_from) {
    const today = new Date().toISOString().split('T')[0];
    if (props.queryParams.date_from === today) {
      filters.dateRange = 'today';
    }
  }
});
</script>