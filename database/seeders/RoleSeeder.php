<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'superadmin',
                'description' => 'Súper Administrador - tiene todos los permisos',
            ],
            [
                'name' => 'admin',
                'description' => 'Administrador - puede gestionar usuarios y configuración del sistema',
            ],
            [
                'name' => 'manager',
                'description' => 'Gerente - puede gestionar usuarios y ver reportes',
            ],
            [
                'name' => 'user',
                'description' => 'Usuario Regular - acceso básico al sistema',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                ['description' => $role['description']]
            );
        }
    }
}
