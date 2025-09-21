<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data and pivot tables
        DB::table('permission_role')->delete();
        Permission::query()->delete();

        $permissions = [
            // User management
            ['name' => 'view_users', 'description' => 'Ver lista de usuarios'],
            ['name' => 'create_users', 'description' => 'Crear nuevos usuarios'],
            ['name' => 'edit_users', 'description' => 'Editar usuarios existentes'],
            ['name' => 'delete_users', 'description' => 'Eliminar usuarios'],
            
            // Role and permission management
            ['name' => 'view_roles', 'description' => 'Ver lista de roles'],
            ['name' => 'manage_roles', 'description' => 'Crear, editar y eliminar roles'],
            ['name' => 'assign_roles', 'description' => 'Asignar roles a usuarios'],
            
            // System settings
            ['name' => 'view_logs', 'description' => 'Ver registros del sistema'],
            ['name' => 'manage_settings', 'description' => 'Gestionar configuración del sistema'],
            ['name' => 'change_app_config', 'description' => 'Cambiar nombre de app, logo, etc. (Solo SuperAdmin)'],
            
            // Reports
            ['name' => 'view_reports', 'description' => 'Ver reportes del sistema'],
            ['name' => 'export_data', 'description' => 'Exportar datos del sistema'],
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                ['description' => $permission['description']]
            );
        }

        // Assign permissions to roles
        $this->assignPermissionsToRoles();
    }

    private function assignPermissionsToRoles(): void
    {
        // SuperAdmin gets all permissions (handled in code, not database)
        // Admin role permissions
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminPermissions = [
                'view_users', 'create_users', 'edit_users', 'delete_users',
                'view_roles', 'manage_roles', 'assign_roles',
                'view_logs', 'manage_settings',
                'view_reports', 'export_data'
            ];
            $adminRole->permissions()->sync(
                Permission::whereIn('name', $adminPermissions)->pluck('id')
            );
        }

        // Manager role permissions
        $managerRole = Role::where('name', 'manager')->first();
        if ($managerRole) {
            $managerPermissions = [
                'view_users', 'create_users', 'edit_users',
                'view_roles',
                'view_reports', 'export_data'
            ];
            $managerRole->permissions()->sync(
                Permission::whereIn('name', $managerPermissions)->pluck('id')
            );
        }

        // User role permissions
        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            $userPermissions = [
                'view_users'
            ];
            $userRole->permissions()->sync(
                Permission::whereIn('name', $userPermissions)->pluck('id')
            );
        }
    }
}
