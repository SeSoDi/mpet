<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\VentaMensualController;
use App\Http\Controllers\UnidadController;
use App\Http\Controllers\FacturacionMensualController;

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

// Monthly Sales management routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('monthly-sales/list', [VentaMensualController::class, 'list'])->name('monthly-sales.list');
    Route::resource('monthly-sales', VentaMensualController::class);
    Route::get('monthly-sales-capture', [VentaMensualController::class, 'capture'])->name('monthly-sales.capture');
});

// Units (Unidades) management routes - Configuration section
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('units', UnidadController::class)->names([
        'index' => 'units.index',
        'create' => 'units.create', 
        'store' => 'units.store',
        'show' => 'units.show',
        'edit' => 'units.edit',
        'update' => 'units.update',
        'destroy' => 'units.destroy'
    ]);
    Route::get('api/units/active', [UnidadController::class, 'active'])->name('units.active');
});

// Monthly Billing (Facturación Mensual) management routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('monthly-billing/list', [FacturacionMensualController::class, 'list'])->name('monthly-billing.list');
    Route::resource('monthly-billing', FacturacionMensualController::class)->names([
        'index' => 'monthly-billing.index',
        'create' => 'monthly-billing.create',
        'store' => 'monthly-billing.store',
        'show' => 'monthly-billing.show',
        'edit' => 'monthly-billing.edit',
        'update' => 'monthly-billing.update',
        'destroy' => 'monthly-billing.destroy'
    ]);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
