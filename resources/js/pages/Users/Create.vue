
<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Role } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { ref } from 'vue';

const props = defineProps<{
    roles: Role[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Users', href: '/users' },
  { title: 'Crear usuario', href: '/users/create' },
];

const selectedRoles = ref<number[]>([]);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role_ids: [] as number[],
});

const submit = () => {
    form.role_ids = selectedRoles.value;
    form.post('/users');
};
</script>

<template>
    <Head title="Crear usuario" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-lg mx-auto mt-8">
            <h1 class="text-2xl font-bold mb-6">Crear usuario</h1>
            <form @submit.prevent="submit" class="flex flex-col gap-6">
                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <Label for="name">Nombre</Label>
                        <Input
                            id="name"
                            type="text"
                            required
                            autofocus
                            autocomplete="name"
                            v-model="form.name"
                            placeholder="Nombre completo"
                        />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Correo electrónico</Label>
                        <Input
                            id="email"
                            type="email"
                            required
                            autocomplete="email"
                            v-model="form.email"
                            placeholder="email@example.com"
                        />
                        <InputError :message="form.errors.email" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password">Contraseña</Label>
                        <Input
                            id="password"
                            type="password"
                            required
                            autocomplete="new-password"
                            v-model="form.password"
                            placeholder="Contraseña"
                        />
                        <InputError :message="form.errors.password" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password_confirmation">Confirmar contraseña</Label>
                        <Input
                            id="password_confirmation"
                            type="password"
                            required
                            autocomplete="new-password"
                            v-model="form.password_confirmation"
                            placeholder="Confirmar contraseña"
                        />
                        <InputError :message="form.errors.password_confirmation" />
                    </div>

                    <!-- Role Assignment -->
                    <div class="grid gap-3">
                        <Label class="text-base font-semibold">Roles</Label>
                        <div class="grid grid-cols-1 gap-2 p-4 border border-gray-200 rounded-md bg-gray-50" style="text-align: left;">
                            <div v-for="role in roles" :key="role.id" class="block" style="text-align: left;">
                                <label class="flex items-start space-x-2 cursor-pointer" style="text-align: left;">
                                    <input
                                        :id="`role-${role.id}`"
                                        type="checkbox"
                                        :value="role.id"
                                        v-model="selectedRoles"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 mt-0.5 flex-shrink-0"
                                    />
                                    <span class="text-sm font-medium text-gray-900" style="text-align: left;">
                                        {{ role.name }}
                                        <span v-if="role.description" class="text-gray-500 font-normal"> - {{ role.description }}</span>
                                    </span>
                                </label>
                            </div>
                            <p v-if="roles.length === 0" class="text-sm text-gray-500 italic">No hay roles disponibles</p>
                        </div>
                        <InputError :message="form.errors.role_ids" />
                    </div>

                    <Button
                        type="submit"
                        class="mt-2 w-full"
                        :disabled="form.processing"
                        data-test="create-user-button"
                    >
                        Crear usuario
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
