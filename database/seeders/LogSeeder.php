<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Log;
use App\Models\User;

class LogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users for realistic log entries
        $rootUser = User::where('name', 'root')->first();
        $adminUser = User::where('name', 'admin')->first();

        // System startup log
        Log::create([
            'level' => 'INFO',
            'channel' => 'system',
            'message' => 'Sistema iniciado correctamente',
            'context' => [
                'version' => '1.0.0',
                'environment' => 'production',
                'startup_time' => '2.34s'
            ],
            'user_id' => null,
            'user_name' => 'Sistema',
            'logged_at' => now()->subDays(7),
        ]);

        // Database migration log
        Log::create([
            'level' => 'INFO',
            'channel' => 'system',
            'message' => 'Migraciones de base de datos ejecutadas exitosamente',
            'context' => [
                'migrations_run' => 9,
                'time_taken' => '1.2s',
                'database' => 'sqlite'
            ],
            'user_id' => null,
            'user_name' => 'Sistema',
            'logged_at' => now()->subDays(7)->addMinutes(2),
        ]);

        // Initial admin login
        if ($rootUser) {
            Log::create([
                'level' => 'INFO',
                'channel' => 'auth',
                'message' => 'Usuario inició sesión - root',
                'context' => [
                    'action' => 'login',
                    'ip' => '127.0.0.1',
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'url' => 'http://localhost:8000/login',
                    'method' => 'POST'
                ],
                'user_id' => $rootUser->id,
                'user_name' => $rootUser->name,
                'logged_at' => now()->subDays(6),
            ]);
        }

        // User creation activity
        if ($adminUser && $rootUser) {
            Log::create([
                'level' => 'INFO',
                'channel' => 'user_management',
                'message' => 'Usuario creado - admin',
                'context' => [
                    'action' => 'created',
                    'target_user_id' => $adminUser->id,
                    'target_user_name' => $adminUser->name,
                    'email' => $adminUser->email,
                    'created_at' => $adminUser->created_at->toISOString(),
                    'ip' => '127.0.0.1',
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                ],
                'user_id' => $rootUser->id,
                'user_name' => $rootUser->name,
                'logged_at' => now()->subDays(6)->addMinutes(15),
            ]);
        }

        // Role assignment activity
        if ($adminUser && $rootUser) {
            Log::create([
                'level' => 'INFO',
                'channel' => 'role_permission',
                'message' => 'Rol asignado al usuario - admin recibió rol "admin"',
                'context' => [
                    'action' => 'role_assigned',
                    'target_user_id' => $adminUser->id,
                    'target_user_name' => $adminUser->name,
                    'role_assigned' => 'admin',
                    'ip' => '127.0.0.1',
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                ],
                'user_id' => $rootUser->id,
                'user_name' => $rootUser->name,
                'logged_at' => now()->subDays(6)->addMinutes(20),
            ]);
        }

        // Some recent activity
        Log::create([
            'level' => 'INFO',
            'channel' => 'system',
            'message' => 'Backup automático completado exitosamente',
            'context' => [
                'backup_size' => '45.2 MB',
                'backup_location' => '/storage/backups/daily_backup_' . now()->format('Y_m_d') . '.sql',
                'tables_backed_up' => 8,
                'duration' => '3.4s'
            ],
            'user_id' => null,
            'user_name' => 'Sistema',
            'logged_at' => now()->subHours(2),
        ]);

        // A warning log
        Log::create([
            'level' => 'WARNING',
            'channel' => 'auth',
            'message' => 'Múltiples intentos de inicio de sesión fallidos detectados',
            'context' => [
                'ip' => '192.168.1.100',
                'attempted_username' => 'admin',
                'failed_attempts' => 3,
                'time_window' => '5 minutes',
                'blocked_until' => now()->addMinutes(15)->toISOString()
            ],
            'user_id' => null,
            'user_name' => 'Sistema',
            'logged_at' => now()->subHours(1),
        ]);

        // A recent info log
        Log::create([
            'level' => 'INFO',
            'channel' => 'system',
            'message' => 'Cache limpiado automáticamente',
            'context' => [
                'cache_size_before' => '23.5 MB',
                'cache_size_after' => '0 MB',
                'items_cleared' => 1247,
                'time_taken' => '0.8s'
            ],
            'user_id' => null,
            'user_name' => 'Sistema',
            'logged_at' => now()->subMinutes(30),
        ]);

        // Current activity - logs seeded
        Log::create([
            'level' => 'INFO',
            'channel' => 'system',
            'message' => 'Logs de ejemplo sembrados en la base de datos',
            'context' => [
                'logs_created' => 8,
                'seeder' => 'LogSeeder',
                'purpose' => 'Demostración inicial del sistema',
                'environment' => app()->environment()
            ],
            'user_id' => null,
            'user_name' => 'Sistema',
            'logged_at' => now(),
        ]);
    }
}
