<template>
  <div class="max-w-lg mx-auto bg-white rounded-lg shadow p-6 space-y-6">
    <div class="flex items-center gap-4">
      <!-- User avatar placeholder -->
      <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center text-2xl font-bold text-gray-500">
        {{ userInitials }}
      </div>
      <div>
        <div class="text-xl font-semibold text-gray-900">{{ user.name }}</div>
        <div class="text-sm text-gray-500">{{ user.email }}</div>
      </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
      <div>
        <div class="text-xs text-gray-400 uppercase">Roles</div>
        <div class="text-sm text-gray-700">
          <ul class="list-disc ml-4">
            <li v-for="role in user.roles" :key="role.id">
              {{ role.name }}
              <ul class="list-disc ml-4 text-xs text-gray-500">
                <li v-for="perm in role.permissions" :key="perm.id">
                  {{ perm.name }}
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
      <div>
        <div class="text-xs text-gray-400 uppercase">Phone</div>
        <div class="text-sm text-gray-700">{{ user.phone || '—' }}</div>
      </div>
      <div>
        <div class="text-xs text-gray-400 uppercase">Title</div>
        <div class="text-sm text-gray-700">{{ user.title || '—' }}</div>
      </div>
      <!-- Add more fields as needed -->
    </div>
  </div>
</template>

<script setup lang="ts">
// Props: user object with roles and permissions
// Example structure:
// user = {
//   name: string,
//   email: string,
//   phone?: string,
//   title?: string,
//   roles: [
//     { id: number, name: string, permissions: [ { id: number, name: string } ] }
//   ]
// }
import { computed } from 'vue';
const props = defineProps<{ user: any }>();
const userInitials = computed(() => {
  if (!props.user.name) return '';
  return props.user.name.split(' ').map((n: string) => n[0]).join('').toUpperCase();
});
</script>
