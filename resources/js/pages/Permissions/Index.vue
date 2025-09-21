<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Permission } from '@/types';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, watch, nextTick } from 'vue';
import { formatDistanceToNow } from 'date-fns';
import { es } from 'date-fns/locale';

const showDeleteModal = ref(false);
const permissionToDelete = ref<Permission | null>(null);
const cancelButton = ref<HTMLButtonElement | null>(null);

function openDeleteModal(permission: Permission) {
    permissionToDelete.value = permission;
    showDeleteModal.value = true;
    nextTick(() => {
        cancelButton.value?.focus();
    });
}

function closeDeleteModal() {
    showDeleteModal.value = false;
    permissionToDelete.value = null;
}

function confirmDelete() {
    if (permissionToDelete.value) {
        router.delete(`/permissions/${permissionToDelete.value.id}`);
        closeDeleteModal();
    }
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Administración de Usuarios',
    },
    {
        title: 'Permisos',
        href: '/permissions',
    },
];

defineProps<{
    permissions: Permission[];
}>();

interface FlashProps {
    success?: string;
    error?: string;
}
interface Quote {
    message: string;
    author: string;
}
interface PageProps {
    flash?: FlashProps;
    name: string;
    quote: Quote;
    auth: any;
    sidebarOpen: boolean;
    [key: string]: any;
}
const page = usePage<PageProps>();
const showNotification = ref(false);
const notificationMessage = ref('');
const notificationType = ref<'success' | 'error'>('success');

watch(
    () => [page.props.flash?.success, page.props.flash?.error],
    ([success, error]) => {
        if (success) {
            notificationMessage.value = success;
            notificationType.value = 'success';
            showNotification.value = true;
            setTimeout(() => {
                showNotification.value = false;
            }, 3000);
        } else if (error) {
            notificationMessage.value = error;
            notificationType.value = 'error';
            showNotification.value = true;
            setTimeout(() => {
                showNotification.value = false;
            }, 3000);
        }
    },
    { immediate: true }
);

function canDeletePermission(permission: Permission): boolean {
    // Prevent deletion of permissions assigned to roles
    return !permission.roles_count || permission.roles_count === 0;
}

function getPermissionCategory(permissionName: string): string {
    if (permissionName.includes('user')) return 'Usuarios';
    if (permissionName.includes('role')) return 'Roles';
    if (permissionName.includes('permission')) return 'Permisos';
    if (permissionName.includes('log')) return 'Logs';
    return 'General';
}

function getCategoryColor(category: string): string {
    switch (category) {
        case 'Usuarios': return 'bg-blue-100 text-blue-800';
        case 'Roles': return 'bg-green-100 text-green-800';
        case 'Permisos': return 'bg-purple-100 text-purple-800';
        case 'Logs': return 'bg-yellow-100 text-yellow-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}
</script>

<template>
    <Head title="Permisos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="overflow-x-auto container flex justify-center mx-auto">
            <div
                v-if="showNotification"
                :class="[
                    'fixed top-6 left-1/2 z-50 -translate-x-1/2 rounded px-6 py-3 text-white shadow-lg transition-all',
                    notificationType === 'success' ? 'bg-green-600' : 'bg-red-600'
                ]"
            >
                {{ notificationMessage }}
            </div>
            <div class="border-b border-gray-200 shadow">
                <Link 
                    href="/permissions/create"
                    class="mt-2 cursor-pointer px-3 py-2 text-white bg-green-600 rounded-md">
                    Crear Permiso
                </Link>

                <table class="mt-3 divide-y divide-gray-300 ">
                    <thead class="_bg-gray-50">
                        <tr>
                            <th class="px-6 py-2 text-sm text-gray-500">ID</th>
                            <th class="px-6 py-2 text-sm text-gray-500">Nombre</th>
                            <th class="px-6 py-2 text-sm text-gray-500">Descripción</th>
                            <th class="px-6 py-2 text-sm text-gray-500">Categoría</th>
                            <th class="px-6 py-2 text-sm text-gray-500">Roles</th>
                            <th class="px-6 py-2 text-sm text-gray-500">Creado</th>
                            <th class="px-6 py-2 text-sm text-gray-500">Ver</th>
                            <th class="px-6 py-2 text-sm text-gray-500">Editar</th>
                            <th class="px-6 py-2 text-sm text-gray-500">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody class="_bg-white divide-y divide-gray-300">
                        <tr v-for="permission in permissions" :key="permission.id" class="whitespace-nowrap">
                            <td class="px-6 py-4 text-sm text-gray-500">{{ permission.id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-100">
                                <code class="px-2 py-1 bg-gray-800 text-gray-200 rounded text-xs font-mono">
                                    {{ permission.name }}
                                </code>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-100 max-w-xs">
                                <span class="truncate block" :title="permission.description">
                                    {{ permission.description }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-100">
                                <span 
                                    :class="[
                                        'px-2 py-1 rounded text-xs font-semibold',
                                        getCategoryColor(getPermissionCategory(permission.name))
                                    ]"
                                >
                                    {{ getPermissionCategory(permission.name) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-100 text-center">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                    {{ permission.roles_count || 0 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-100 text-center">
                                {{ formatDistanceToNow(new Date(permission.created_at), { addSuffix: false, locale: es }) }}
                            </td>
                            <td class="px-6 py-4">
                                <Link
                                    :href="`/permissions/${permission.id}`"
                                    class="px-4 py-1 text-sm text-blue-900 bg-blue-200 rounded-full hover:bg-blue-300 transition-colors"
                                >
                                    Mostrar
                                </Link>
                            </td>
                            <td class="px-6 py-4">
                                <Link
                                    :href="`/permissions/${permission.id}/edit`"
                                    class="px-4 py-1 text-sm text-green-900 bg-green-200 rounded-full hover:bg-green-300 transition-colors"
                                >
                                    Editar
                                </Link>
                            </td>
                            <td class="px-6 py-4">
                                <button
                                    v-if="canDeletePermission(permission)"
                                    @click="openDeleteModal(permission)"
                                    class="px-4 py-1 text-sm text-white bg-red-600 rounded-full hover:bg-red-700 transition-colors"
                                    type="button"
                                >
                                    Eliminar
                                </button>
                                <span
                                    v-else
                                    class="px-4 py-1 text-sm text-gray-500 bg-gray-200 rounded-full cursor-not-allowed"
                                    title="No se puede eliminar permisos asignados a roles"
                                >
                                    En uso
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <!-- Delete Confirmation Modal -->
                <template v-if="showDeleteModal">
                    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                        <div class="bg-white p-6 rounded shadow-lg min-w-[300px] text-gray-900">
                            <p class="text-base font-semibold mb-2">Confirmar eliminación</p>
                            <p class="text-sm mb-4">
                                ¿Estás seguro de que deseas eliminar el permiso 
                                <code class="font-mono bg-gray-100 px-1">"{{ permissionToDelete?.name }}"</code>? 
                                Esta acción no se puede deshacer.
                            </p>
                            <div class="mt-4 flex justify-end gap-2">
                                <button
                                    ref="cancelButton"
                                    @click="closeDeleteModal"
                                    class="px-4 py-2 bg-gray-200 text-gray-900 rounded hover:bg-gray-300"
                                >
                                    Cancelar
                                </button>
                                <button
                                    @click="confirmDelete"
                                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                                >
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </AppLayout>
</template>