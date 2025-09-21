<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Permission } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { formatDistanceToNow } from 'date-fns';
import { es } from 'date-fns/locale';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Administración de Usuarios',
    },
    {
        title: 'Permisos',
        href: '/permissions',
    },
    {
        title: 'Ver Permiso',
    },
];

const props = defineProps<{
    permission: Permission;
}>();

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

const canDeletePermission = (): boolean => {
    return !props.permission.roles_count || props.permission.roles_count === 0;
};
</script>

<template>
    <Head :title="`Permiso: ${permission.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-4xl">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <h1 class="text-2xl font-bold text-gray-900 font-mono">
                                {{ permission.name }}
                            </h1>
                            <span 
                                :class="[
                                    'px-3 py-1 text-sm rounded-full',
                                    getCategoryColor(getPermissionCategory(permission.name))
                                ]"
                            >
                                {{ getPermissionCategory(permission.name) }}
                            </span>
                        </div>
                        <div class="flex space-x-2">
                            <Link
                                :href="`/permissions/${permission.id}/edit`"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700"
                            >
                                Editar
                            </Link>
                            <Link
                                href="/permissions"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                            >
                                Volver
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="space-y-4">
                            <h2 class="text-lg font-semibold text-gray-900">Información Básica</h2>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">ID</label>
                                <p class="mt-1 text-sm text-gray-900">{{ permission.id }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Nombre</label>
                                <p class="mt-1">
                                    <code class="px-2 py-1 bg-gray-800 text-gray-200 rounded text-sm font-mono">
                                        {{ permission.name }}
                                    </code>
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Descripción</label>
                                <p class="mt-1 text-sm text-gray-900">{{ permission.description || 'Sin descripción' }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Categoría</label>
                                <p class="mt-1">
                                    <span 
                                        :class="[
                                            'px-2.5 py-0.5 rounded text-xs font-semibold',
                                            getCategoryColor(getPermissionCategory(permission.name))
                                        ]"
                                    >
                                        {{ getPermissionCategory(permission.name) }}
                                    </span>
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Roles Asignados</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ permission.roles_count || 0 }} roles
                                    </span>
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Creado</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ formatDistanceToNow(new Date(permission.created_at), { addSuffix: true, locale: es }) }}
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Actualizado</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ formatDistanceToNow(new Date(permission.updated_at), { addSuffix: true, locale: es }) }}
                                </p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-500">Estado</label>
                                <p class="mt-1">
                                    <span 
                                        :class="[
                                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                            canDeletePermission() ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'
                                        ]"
                                    >
                                        {{ canDeletePermission() ? 'Disponible' : 'En uso' }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Roles with this permission -->
                        <div class="space-y-4">
                            <h2 class="text-lg font-semibold text-gray-900">
                                Roles que tienen este permiso ({{ permission.roles?.length || 0 }})
                            </h2>
                            
                            <div v-if="!permission.roles || permission.roles.length === 0" 
                                 class="text-gray-500 text-center py-8">
                                <p>Este permiso no está asignado a ningún rol</p>
                            </div>
                            
                            <div v-else class="space-y-3">
                                <div
                                    v-for="role in permission.roles"
                                    :key="role.id"
                                    class="p-3 border border-gray-200 rounded-lg hover:bg-gray-50"
                                >
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-900">
                                                <Link 
                                                    :href="`/roles/${role.id}`"
                                                    class="text-blue-600 hover:text-blue-800"
                                                >
                                                    {{ role.name }}
                                                </Link>
                                            </h3>
                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ role.description }}
                                            </p>
                                        </div>
                                        <span 
                                            :class="[
                                                'inline-flex items-center px-2 py-1 rounded text-xs font-medium',
                                                role.name.toLowerCase() === 'superadmin' ? 'bg-purple-100 text-purple-800' :
                                                role.name.toLowerCase() === 'admin' ? 'bg-blue-100 text-blue-800' :
                                                role.name.toLowerCase() === 'manager' ? 'bg-green-100 text-green-800' :
                                                'bg-gray-100 text-gray-800'
                                            ]"
                                        >
                                            {{ role.name }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>