<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('users', UserController::class)->middleware(['auth', 'verified']);

// Role and Permission management routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
});

// Logs management routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('logs', [LogController::class, 'index'])->name('logs.index');
    Route::get('logs/{log}', [LogController::class, 'show'])->name('logs.show');
    Route::delete('logs/{log}', [LogController::class, 'destroy'])->name('logs.destroy');
    Route::post('logs/bulk-delete', [LogController::class, 'bulkDelete'])->name('logs.bulk-delete');
    Route::get('api/logs/stats', [LogController::class, 'stats'])->name('logs.stats');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
