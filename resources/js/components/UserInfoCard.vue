<template>
  <div class="max-w-lg mx-auto bg-white rounded-lg shadow p-6 space-y-6">
    <div class="flex items-center gap-4">
      <!-- Avatar con iconos -->
      <div class="relative">
        <div class="w-20 h-20 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
          <Shield v-if="user.name === 'root'" class="w-10 h-10 text-red-600" />
          <Shield v-else-if="user.name === 'admin'" class="w-10 h-10 text-blue-600" />
          <CircleUser v-else class="w-10 h-10 text-gray-600" />
        </div>
      </div>
      
      <div>
        <div class="flex items-center gap-2">
          <span class="text-xl font-semibold text-gray-900">{{ user.name }}</span>
          <!-- Show SuperAdmin icon next to name -->
          <ShieldCheck 
            v-if="isSuperAdmin" 
            class="h-5 w-5 text-red-600" 
            title="SuperAdmin - Tiene todos los permisos"
          />
        </div>
        <div class="text-sm text-gray-500">{{ user.email }}</div>
      </div>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
      <div class="sm:col-span-2">
        <div class="text-xs text-gray-400 uppercase">Roles</div>
        <div class="text-sm text-gray-700 mt-1">
          <div v-if="user.roles && user.roles.length > 0" class="flex flex-wrap gap-2">
            <span 
              v-for="role in user.roles" 
              :key="role.id"
              :class="getRoleBadgeClass(role.name)"
              class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
            >
              {{ getRoleDisplayName(role.name) }}
            </span>
          </div>
          <span v-else class="text-gray-400">Sin roles asignados</span>
        </div>
      </div>
      
      <div class="sm:col-span-2">
        <div class="text-xs text-gray-400 uppercase">Permisos</div>
        <div class="text-sm text-gray-700 mt-1">
          <div v-if="isSuperAdmin" class="text-green-600 font-medium">
            Todos los permisos (SuperAdmin)
          </div>
          <div v-else-if="allPermissions.length > 0" class="flex flex-wrap gap-1">
            <span 
              v-for="permission in allPermissions" 
              :key="permission.id"
              class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
            >
              {{ getPermissionDisplayName(permission.name) }}
            </span>
          </div>
          <span v-else class="text-gray-400">Sin permisos específicos</span>
        </div>
      </div>
      
      <div>
        <div class="text-xs text-gray-400 uppercase">Teléfono</div>
        <div class="text-sm text-gray-700">{{ user.phone || '—' }}</div>
      </div>
      <div>
        <div class="text-xs text-gray-400 uppercase">Cargo</div>
        <div class="text-sm text-gray-700">{{ user.title || '—' }}</div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { ShieldCheck, Shield, CircleUser } from 'lucide-vue-next';

const props = defineProps<{ user: any }>();

const userInitials = computed(() => {
  if (!props.user.name) return '';
  return props.user.name.split(' ').map((n: string) => n[0]).join('').toUpperCase();
});

const isSuperAdmin = computed(() => {
  return props.user.roles?.some((role: any) => role.name === 'superadmin') || false;
});

const allPermissions = computed(() => {
  if (!props.user.roles) return [];
  
  const permissions = [];
  for (const role of props.user.roles) {
    if (role.permissions) {
      permissions.push(...role.permissions);
    }
  }
  
  // Remove duplicates
  return permissions.filter((permission, index, self) => 
    index === self.findIndex(p => p.id === permission.id)
  );
});

const getRoleDisplayName = (roleName: string): string => {
  const roleNames: Record<string, string> = {
    'superadmin': 'SuperAdmin',
    'admin': 'Administrador',
    'manager': 'Gerente',
    'user': 'Usuario',
  };
  return roleNames[roleName] || roleName;
};

const getRoleBadgeClass = (roleName: string): string => {
  const classes: Record<string, string> = {
    'superadmin': 'bg-red-100 text-red-800',
    'admin': 'bg-purple-100 text-purple-800',
    'manager': 'bg-yellow-100 text-yellow-800',
    'user': 'bg-gray-100 text-gray-800',
  };
  return classes[roleName] || 'bg-gray-100 text-gray-800';
};

const getPermissionDisplayName = (permissionName: string): string => {
  const permissionNames: Record<string, string> = {
    'view_users': 'Ver usuarios',
    'create_users': 'Crear usuarios',
    'edit_users': 'Editar usuarios',
    'delete_users': 'Eliminar usuarios',
    'view_roles': 'Ver roles',
    'manage_roles': 'Gestionar roles',
    'assign_roles': 'Asignar roles',
    'view_logs': 'Ver logs',
    'manage_settings': 'Gestionar configuración',
    'change_app_config': 'Cambiar configuración de app',
    'view_reports': 'Ver reportes',
    'export_data': 'Exportar datos',
  };
  return permissionNames[permissionName] || permissionName;
};
</script>
