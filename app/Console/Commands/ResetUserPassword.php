<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResetUserPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:reset-password {username : The username of the user} {--password= : The new password (if not provided, will be prompted)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset a user\'s password';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $username = $this->argument('username');
        $password = $this->option('password');

        // Validate username format
        if (empty(trim($username))) {
            $this->error('Username cannot be empty.');
            return Command::FAILURE;
        }

        // Find the user by name
        $user = User::where('name', $username)->first();
        
        if (!$user) {
            $this->error("User with username '{$username}' not found.");
            return Command::FAILURE;
        }

        // Get password if not provided
        if (!$password) {
            $password = $this->secret('Enter new password');
            
            if (empty($password)) {
                $this->error('Password cannot be empty.');
                return Command::FAILURE;
            }

            $confirmPassword = $this->secret('Confirm new password');
            
            if ($password !== $confirmPassword) {
                $this->error('Passwords do not match.');
                return Command::FAILURE;
            }
        }

        // Validate password strength
        if (strlen($password) < 8) {
            $this->error('Password must be at least 8 characters long.');
            return Command::FAILURE;
        }

        // Show user info before confirmation
        $this->info("User found:");
        $this->info("  Name: {$user->name}");
        $this->info("  Email: {$user->email}");
        $this->info("  Roles: " . $user->roles->pluck('name')->join(', '));

        // Confirm the action
        if (!$this->confirm('Do you want to reset this user\'s password?')) {
            $this->info('Password reset cancelled.');
            return Command::SUCCESS;
        }

        // Reset the password
        $user->password = Hash::make($password);
        $user->save();

        $this->info("Password successfully reset for user: {$user->name} ({$user->email})");
        
        return Command::SUCCESS;
    }
}
