<script setup lang="ts">

import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, User } from '@/types';
import { Head, Form, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { computed } from 'vue';
import UserInfoCard from '@/components/UserInfoCard.vue';

const props = defineProps<{ user: User }>();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Users', href: '/users' },
  { title: 'Mostrar usuario', href: `/users/${props.user.id}/show` },
];

</script>

<template>
    <Head title="Mostrar usuario" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-lg mx-auto mt-8">
            <h1 class="text-2xl font-bold mb-6">Información del usuario</h1>
            <UserInfoCard :user="props.user" />
        </div>
    </AppLayout>
</template>
