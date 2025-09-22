<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Role } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { formatDistanceToNow } from 'date-fns';
import { es } from 'date-fns/locale';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Administración de Usuarios',
    },
    {
        title: 'Roles',
        href: '/roles',
    },
    {
        title: 'Ver Rol',
    },
];

const props = defineProps<{
    role: Role;
}>();

const isSystemRole = (roleName: string): boolean => {
    const systemRoles = ['superadmin', 'admin'];
    return systemRoles.includes(roleName.toLowerCase());
};
</script>

<template>
    <Head :title="`Rol: ${role.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-4xl">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <h1 class="text-2xl font-bold text-gray-900">
                                {{ role.name }}
                            </h1>
                            <span 
                                v-if="isSystemRole(role.name)"
                                class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm rounded-full"
                            >
                                Rol del Sistema
                            </span>
                        </div>
                        <div class="flex space-x-2">
                            <Link
                                :href="`/roles/${role.id}/edit`"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700"
                            >
                                Editar
                            </Link>
                            <Link
                                href="/roles"
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
                                <p class="mt-1 text-sm text-gray-900">{{ role.id }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Nombre</label>
                                <p class="mt-1 text-sm text-gray-900">{{ role.name }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Descripción</label>
                                <p class="mt-1 text-sm text-gray-900">{{ role.description }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Usuarios Asignados</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ role.users_count || 0 }} usuarios
                                    </span>
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Creado</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ formatDistanceToNow(new Date(role.created_at), { addSuffix: true, locale: es }) }}
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Actualizado</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ formatDistanceToNow(new Date(role.updated_at), { addSuffix: true, locale: es }) }}
                                </p>
                            </div>
                        </div>

                        <!-- Permissions -->
                        <div class="space-y-4">
                            <h2 class="text-lg font-semibold text-gray-900">
                                Permisos ({{ role.permissions?.length || 0 }})
                            </h2>
                            
                            <div v-if="!role.permissions || role.permissions.length === 0" 
                                 class="text-gray-500 text-center py-8">
                                <p>Este rol no tiene permisos asignados</p>
                            </div>
                            
                            <div v-else class="space-y-3">
                                <div
                                    v-for="permission in role.permissions"
                                    :key="permission.id"
                                    class="p-3 border border-gray-200 rounded-lg hover:bg-gray-50"
                                >
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-900">
                                                {{ permission.name }}
                                            </h3>
                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ permission.description }}
                                            </p>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                            Activo
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users with this role -->
                <div v-if="role.users && role.users.length > 0" class="border-t border-gray-200">
                    <div class="px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            Usuarios con este rol ({{ role.users.length }})
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div
                                v-for="user in role.users"
                                :key="user.id"
                                class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50"
                            >
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 bg-blue-600 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-white">
                                            {{ user.name.charAt(0).toUpperCase() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ user.name }}
                                    </p>
                                    <p class="text-sm text-gray-500 truncate">
                                        {{ user.email }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>