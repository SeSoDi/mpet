<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rootPassword = env('ROOT_USER_PASSWORD');
        
        if ($rootPassword && $rootPassword !== 'your_strong_password_here') {
            // Create root user directly (without factory)
            $rootUser = User::create([
                'name' => 'root',
                'email' => 'root@notAnyDomain.local',
                'password' => Hash::make($rootPassword),
                'email_verified_at' => now(),
            ]);
            
            // Assign superadmin role to root user
            $superadminRole = \App\Models\Role::where('name', 'superadmin')->first();
            if ($superadminRole) {
                $rootUser->roles()->attach($superadminRole->id);
            }

            // Create admin user directly (without factory)
            $adminUser = User::create([
                'name' => 'admin',
                'email' => 'admin@example.local',
                'password' => Hash::make($rootPassword),
                'email_verified_at' => now(),
            ]);
            
            // Assign admin role to admin user
            $adminRole = \App\Models\Role::where('name', 'admin')->first();
            if ($adminRole) {
                $adminUser->roles()->attach($adminRole->id);
            }

            echo "✅ Created users with password from ROOT_USER_PASSWORD\n";
        } else {
            echo "❌ ROOT_USER_PASSWORD is not set or is 'your_strong_password_here'. Skipping user seeding.\n";
        }
    }
}