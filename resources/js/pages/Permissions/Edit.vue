<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Permission } from '@/types';
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
        title: 'Editar Permiso',
    },
];

const props = defineProps<{
    permission: Permission;
}>();

const form = useForm({
    name: props.permission.name,
    description: props.permission.description || '',
});

const submit = () => {
    form.put(`/permissions/${props.permission.id}`);
};

const canEditPermission = (): boolean => {
    return !props.permission.roles_count || props.permission.roles_count === 0;
};
</script>

<template>
    <Head :title="`Editar Permiso: ${permission.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-2xl">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">
                        Editar Permiso: <code class="text-lg">{{ permission.name }}</code>
                    </h1>
                    <span 
                        v-if="!canEditPermission()"
                        class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm rounded-full"
                    >
                        En uso
                    </span>
                </div>
                
                <div v-if="!canEditPermission()" class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                    <div class="flex">
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">
                                Permiso en uso
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>
                                    Este permiso está asignado a {{ permission.roles_count }} role(s). 
                                    Cambiar el nombre puede afectar la funcionalidad del sistema.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
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
                            :disabled="!canEditPermission()"
                            class="font-mono text-sm"
                            placeholder="ej: user.create, post.edit, admin.access"
                            required
                        />
                        <p v-if="!canEditPermission()" class="mt-1 text-sm text-gray-500">
                            El nombre no se puede modificar porque está en uso por roles
                        </p>
                        <p v-else class="mt-1 text-sm text-gray-500">
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

                    <!-- Usage Information -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Información de Uso</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Roles asignados: </span>&nbsp;
                                <span class="font-bold text-gray-900">{{ permission.roles_count || 0 }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">ID: </span>&nbsp;
                                <span class="font-bold text-gray-900">{{ permission.id }}</span>
                            </div>
                        </div>
                        <div v-if="permission.roles && permission.roles.length > 0" class="mt-3">
                            <span class="text-gray-500 text-sm">Usado por roles:</span>
                            <div class="mt-1 space-x-1">
                                <Link 
                                    v-for="role in permission.roles" 
                                    :key="role.id"
                                    :href="`/roles/${role.id}`"
                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200"
                                >
                                    {{ role.name }}
                                </Link>
                            </div>
                        </div>
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
                            <span v-if="form.processing">Actualizando...</span>
                            <span v-else>Actualizar Permiso</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>