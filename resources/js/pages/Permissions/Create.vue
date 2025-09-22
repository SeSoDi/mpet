<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Administración de Usuarios',
    },
    {
        title: 'Permisos',
        href: '/permissions',
    },
    {
        title: 'Crear Permiso',
    },
];

const form = useForm({
    name: '',
    description: '',
});

const submit = () => {
    form.post('/permissions');
};

const predefinedPermissions = [
    { name: 'user.create', description: 'Crear usuarios' },
    { name: 'user.read', description: 'Ver usuarios' },
    { name: 'user.update', description: 'Actualizar usuarios' },
    { name: 'user.delete', description: 'Eliminar usuarios' },
    { name: 'role.create', description: 'Crear roles' },
    { name: 'role.read', description: 'Ver roles' },
    { name: 'role.update', description: 'Actualizar roles' },
    { name: 'role.delete', description: 'Eliminar roles' },
    { name: 'permission.create', description: 'Crear permisos' },
    { name: 'permission.read', description: 'Ver permisos' },
    { name: 'permission.update', description: 'Actualizar permisos' },
    { name: 'permission.delete', description: 'Eliminar permisos' },
    { name: 'log.read', description: 'Ver logs del sistema' },
];

const usePreset = (preset: { name: string; description: string }) => {
    form.name = preset.name;
    form.description = preset.description;
};
</script>

<template>
    <Head title="Crear Permiso" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-2xl">
            <div class="bg-white shadow rounded-lg p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">Crear Nuevo Permiso</h1>
                
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Permission Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre del Permiso *
                        </label>
                        <Input
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="font-mono text-sm"
                            placeholder="ej: user.create, post.edit, admin.access"
                            required
                        />
                        <p class="mt-1 text-sm text-gray-500">
                            Use formato punto para agrupar permisos (ej: user.create, role.delete)
                        </p>
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- Permission Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción
                        </label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                            :class="{ 'border-red-500': form.errors.description }"
                            placeholder="Descripción clara de lo que permite este permiso"
                        />
                        <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                            {{ form.errors.description }}
                        </p>
                    </div>

                    <!-- Predefined Permissions -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Permisos Sugeridos
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <button
                                v-for="preset in predefinedPermissions"
                                :key="preset.name"
                                type="button"
                                @click="usePreset(preset)"
                                class="text-left p-2 border border-gray-200 rounded hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <div class="font-mono text-sm text-blue-600">{{ preset.name }}</div>
                                <div class="text-xs text-gray-500">{{ preset.description }}</div>
                            </button>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            Haga clic en cualquier permiso sugerido para usar como plantilla
                        </p>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t">
                        <Link
                            href="/permissions"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="form.processing">Creando...</span>
                            <span v-else>Crear Permiso</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>