<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Role, Permission } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Administración de Usuarios',
    },
    {
        title: 'Roles',
        href: '/roles',
    },
    {
        title: 'Editar Rol',
    },
];

const props = defineProps<{
    role: Role;
    permissions: Permission[];
}>();

const form = useForm({
    name: props.role.name,
    description: props.role.description,
    permissions: props.role.permissions?.map(p => p.id) || [],
});

const submit = () => {
    form.put(`/roles/${props.role.id}`);
};

const togglePermission = (permissionId: number) => {
    const index = form.permissions.indexOf(permissionId);
    if (index > -1) {
        form.permissions.splice(index, 1);
    } else {
        form.permissions.push(permissionId);
    }
};

const selectAllPermissions = () => {
    if (form.permissions.length === props.permissions.length) {
        form.permissions = [];
    } else {
        form.permissions = props.permissions.map(p => p.id);
    }
};

const isSystemRole = (roleName: string): boolean => {
    const systemRoles = ['superadmin', 'admin'];
    return systemRoles.includes(roleName.toLowerCase());
};
</script>

<template>
    <Head :title="`Editar Rol: ${role.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-2xl">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">
                        Editar Rol: {{ role.name }}
                    </h1>
                    <span 
                        v-if="isSystemRole(role.name)"
                        class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm rounded-full"
                    >
                        Rol del Sistema
                    </span>
                </div>
                
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Role Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Name
                    </label>
                    <Input
                        id="name"
                        v-model="form.name"
                        type="text"
                        required
                        :disabled="isSystemRole(role.name)"
                        :class="isSystemRole(role.name) ? 'bg-gray-100' : ''"
                    />
                    <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">
                        {{ form.errors.name }}
                    </div>
                </div>                    <!-- Role Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción *
                        </label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                            :class="{ 'border-red-500': form.errors.description }"
                            placeholder="Descripción del rol y sus responsabilidades"
                            required
                        />
                        <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                            {{ form.errors.description }}
                        </p>
                    </div>

                    <!-- Permissions -->
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <label class="block text-sm font-medium text-gray-700">
                                Permisos ({{ form.permissions.length }} seleccionados)
                            </label>
                            <button
                                type="button"
                                @click="selectAllPermissions"
                                class="text-sm text-blue-600 hover:text-blue-800"
                            >
                                {{ form.permissions.length === permissions.length ? 'Deseleccionar todos' : 'Seleccionar todos' }}
                            </button>
                        </div>
                        
                        <div class="border border-gray-300 rounded-md p-4 max-h-64 overflow-y-auto">
                            <div v-if="permissions.length === 0" class="text-gray-500 text-center py-4">
                                No hay permisos disponibles
                            </div>
                            <div v-else class="space-y-2">
                                <label
                                    v-for="permission in permissions"
                                    :key="permission.id"
                                    class="flex items-center space-x-3 p-2 rounded hover:bg-gray-50 cursor-pointer"
                                >
                                    <input
                                        type="checkbox"
                                        :checked="form.permissions.includes(permission.id)"
                                        @change="togglePermission(permission.id)"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    />
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ permission.name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ permission.description }}
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <p v-if="form.errors.permissions" class="mt-1 text-sm text-red-600">
                            {{ form.errors.permissions }}
                        </p>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t">
                        <Link
                            href="/roles"
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
                            <span v-else>Actualizar Rol</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>