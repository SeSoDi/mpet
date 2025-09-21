<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, User } from '@/types';
import { Head, Form, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const props = defineProps<{ user: User }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Usuarios', href: '/users' },
    { title: 'Editar usuario', href: `/users/${props.user.id}/edit` },
];

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
});
</script>

<template>
    <Head title="Editar usuario" />
    <AppLayout :breadcrumbs="breadcrumbs">
    <div class="max-w-xl mx-auto mt-8">
            <h1 class="text-2xl font-bold mb-6">Editar usuario</h1>
            <Form
                method="post"
                :action="`/users/${props.user.id}`"
                @submit.prevent="form.put(`/users/${props.user.id}`)"
                v-slot="{ errors, processing }"
                class="flex flex-col gap-6"
            >
                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <Label for="name">Nombre</Label>
                        <Input
                            id="name"
                            type="text"
                            required
                            autofocus
                            autocomplete="name"
                            name="name"
                            v-model="form.name"
                            placeholder="Nombre completo"
                        />
                        <InputError :message="errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Correo electrónico</Label>
                        <Input
                            id="email"
                            type="email"
                            required
                            autocomplete="email"
                            name="email"
                            v-model="form.email"
                            placeholder="email@example.com"
                        />
                        <InputError :message="errors.email" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password">Contraseña nueva</Label>
                        <Input
                            id="password"
                            type="password"
                            autocomplete="new-password"
                            name="password"
                            v-model="form.password"
                            placeholder="Dejar en blanco para no cambiar"
                        />
                        <InputError :message="errors.password" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="password_confirmation">Confirmar contraseña</Label>
                        <Input
                            id="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            name="password_confirmation"
                            v-model="form.password_confirmation"
                            placeholder="Confirmar contraseña"
                        />
                        <InputError :message="errors.password_confirmation" />
                    </div>

                    <div class="flex flex-row gap-4 mt-2">
                        <Button
                            type="submit"
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold"
                            :disabled="processing"
                            data-test="update-user-button"
                        >
                            Guardar cambios
                        </Button>
                        <Button
                            type="button"
                            class="flex-1 bg-red-100 text-red-700 border border-red-300 hover:bg-red-200 font-semibold"
                            @click="$inertia.visit('/users')"
                            data-test="cancel-edit-user-button"
                        >
                            Cancelar cambios
                        </Button>
                    </div>
                </div>
            </Form>
        </div>
    </AppLayout>
</template>
