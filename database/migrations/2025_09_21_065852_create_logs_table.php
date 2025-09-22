<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            if (DB::getDriverName() === 'mysql') {
                $table->engine = 'MyISAM';
            }
            
            $table->id();
            $table->string('level', 20);                       // DEBUG, INFO, WARNING, ERROR, CRITICAL
            $table->string('channel', 50);                     // auth, role_permission, user_management, etc.
            $table->text('message');                           // Human-readable log message
            $table->json('context')->nullable();               // Additional data (IP, old/new values, etc.)
            $table->unsignedBigInteger('user_id')->nullable(); // Who performed the action (FK)
            $table->string('user_name', 100)->nullable();      // Human-readable username
            $table->timestamp('logged_at');                    // When it happened
            
            // Single indexes
            $table->index('level');
            $table->index('channel');
            $table->index('user_id');
            $table->index('logged_at');
            
            // Composite indexes
            $table->index(['level', 'logged_at']);     // "show all ERROR logs from last week"
            $table->index(['user_id', 'logged_at']);   // "show John's activity timeline"
            $table->index(['channel', 'logged_at']);   // "show all 'auth' logs from last month"
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
