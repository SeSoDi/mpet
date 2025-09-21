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
        // Call role and permission seeders first
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
        ]);

        // Delete all users (respecting FKs), then truncate to reset auto-increment
        User::query()->delete();
        DB::table('users')->truncate();

        $rootPassword = env('ROOT_USER_PASSWORD');
        if ($rootPassword && $rootPassword !== 'your_strong_password_here') {
            $rootUser = User::factory()->create([
                'name' => 'root',
                'email' => 'root@notAnyDomain.local',
                'password' => Hash::make($rootPassword),
            ]);
            
            // Assign superadmin role to root user
            $superadminRole = \App\Models\Role::where('name', 'superadmin')->first();
            if ($superadminRole) {
                $rootUser->roles()->attach($superadminRole->id);
            }
        } else {
            // Log error to console if password is not set or is 'your_strong_password_here'
            echo "[ERROR] ROOT_USER_PASSWORD is not set or is 'your_strong_password_here'. Skipping root user seeding.\n";
        }

        if ($rootPassword && $rootPassword !== 'your_strong_password_here') {
            $adminUser = User::factory()->create([
                'name' => 'admin',
                'email' => 'admin@example.local',
                'password' => Hash::make($rootPassword),
            ]);
            
            // Assign admin role to admin user
            $adminRole = \App\Models\Role::where('name', 'admin')->first();
            if ($adminRole) {
                $adminUser->roles()->attach($adminRole->id);
            }
        } else {
            // Log error to console if password is not set or is 'your_strong_password_here'
            echo "[ERROR] ROOT_USER_PASSWORD is not set or is 'your_strong_password_here'. Skipping admin user seeding.\n";
        }

        // Seed example logs after users are created
        $this->call([
            LogSeeder::class,
        ]);
    }
}
