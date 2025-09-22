<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed in proper order: Cleanup → Roles → Permissions → Users → Logs
        $this->call([
            CleanupSeeder::class,        // 0. Clean existing data first
            RoleSeeder::class,           // 1. Create roles
            PermissionSeeder::class,     // 2. Create permissions 
            UserSeeder::class,           // 3. Create users (depends on roles)
            LogSeeder::class,            // 4. Create logs (depends on users)
        ]);
    }
}
