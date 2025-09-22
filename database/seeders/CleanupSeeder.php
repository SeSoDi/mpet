<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Log;

class CleanupSeeder extends Seeder
{
    /**
     * Clean all data from tables in the correct order (respecting foreign keys).
     */
    public function run(): void
    {
        echo "🧹 Cleaning existing data...\n";

        // Disable foreign key checks for MySQL
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clean data in reverse dependency order
        // 1. Clean logs (depends on users)
        Log::query()->delete();
        DB::table('logs')->truncate();

        // 2. Clean pivot tables (user-role relationships)
        DB::table('role_user')->truncate();
        DB::table('permission_role')->truncate();

        // 3. Clean users (depends on roles via pivot)
        User::query()->delete();
        DB::table('users')->truncate();

        // 4. Clean permissions
        Permission::query()->delete();
        DB::table('permissions')->truncate();

        // 5. Clean roles
        Role::query()->delete();
        DB::table('roles')->truncate();

        // Clean other tables that might exist
        $tablesToClean = ['cache', 'jobs', 'failed_jobs', 'sessions'];
        foreach ($tablesToClean as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        echo "✅ Database cleanup completed!\n";
    }
}