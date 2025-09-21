
<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, User } from '@/types';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, watch, nextTick } from 'vue';
import { formatDistanceToNow } from 'date-fns';
import { es } from 'date-fns/locale';

const showDeleteModal = ref(false);
const userToDelete = ref<User | null>(null);
const cancelButton = ref<HTMLButtonElement | null>(null);

function openDeleteModal(user: User) {
    userToDelete.value = user;
    showDeleteModal.value = true;
    nextTick(() => {
        cancelButton.value?.focus();
    });
}

function closeDeleteModal() {
    showDeleteModal.value = false;
    userToDelete.value = null;
}

function confirmDelete() {
    if (userToDelete.value) {
        router.delete(`/users/${userToDelete.value.id}`);
        closeDeleteModal();
    }
}


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Usuarios',
        href: '/users',
    },
];

defineProps<{
    users: User[];
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
</script>

<template>
    <Head title="Usuarios" />

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
                    href="/users/create"
                    class="mt-2 cursor-pointer px-3 py-2 text-white bg-green-600 rounded-md">
                    Crear usuario
                </Link>

                <table class="mt-3 divide-y divide-gray-300 ">
                    <thead class="_bg-gray-50">
                        <tr>
                            <th class="px-6 py-2 text-sm text-gray-500">ID</th>
                            <th class="px-6 py-2 text-sm text-gray-500">Nombre</th>
                            <th class="px-6 py-2 text-sm text-gray-500">Correo electrónico</th>
                            <th class="px-6 py-2 text-sm text-gray-500">Desde</th>
                            <th class="px-6 py-2 text-sm text-gray-500">Ver</th>
                            <th class="px-6 py-2 text-sm text-gray-500">Editar</th>
                            <th class="px-6 py-2 text-sm text-gray-500">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody class="_bg-white divide-y divide-gray-300">
                        <tr v-for="user in users" :key="user.id" class="whitespace-nowrap">
                            <td class="px-6 py-4 text-sm text-gray-500">{{ user.id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-100">{{ user.name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-100">{{ user.email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-100 text-center">
                                {{ formatDistanceToNow(new Date(user.created_at), { addSuffix: false, locale: es }) }}
                            </td>
                            <td class="px-6 py-4">
                                <Link
                                    :href="`/users/${user.id}`"
                                    class="px-4 py-1 text-sm text-blue-900 bg-blue-200 rounded-full hover:bg-blue-300 transition-colors"
                                >
                                    Mostrar
                                </Link>
                            </td>
                            <td class="px-6 py-4">
                                <Link
                                    :href="`/users/${user.id}/edit`"
                                    class="px-4 py-1 text-sm text-green-900 bg-green-200 rounded-full hover:bg-green-300 transition-colors"
                                >
                                    Editar
                                </Link>
                            </td>
                            <td class="px-6 py-4">
                                <!--
                                    Delete button: Backend validations
                                    - Cannot delete the 'root' user (UserController@destroy)
                                    - Cannot delete your own user (UserController@destroy)
                                    Both cases will show an error message if attempted.
                                -->
                                <button
                                    @click="openDeleteModal(user)"
                                    class="px-4 py-1 text-sm text-white bg-red-600 rounded-full hover:bg-red-700 transition-colors"
                                    type="button"
                                >
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- Delete Confirmation Modal (moved outside the table row loop) -->
                <template v-if="showDeleteModal">
                    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                        <div class="bg-white p-6 rounded shadow-lg min-w-[300px] text-gray-900">
                            <p class="text-base font-semibold mb-2">Confirmar eliminación</p>
                            <p class="text-sm mb-4">¿Estás seguro de que deseas eliminar al usuario <span class="font-bold">"{{ userToDelete?.name }}"</span>? Esta acción no se puede deshacer.</p>
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
            </div> <!-- close .border-b border-gray-200 shadow -->
        </div> <!-- close .overflow-x-auto container flex justify-center mx-auto -->
    </AppLayout>
</template>
