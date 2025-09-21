<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Services\LogService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions.
     */
    public function index(): Response
    {
        $permissions = Permission::withCount('roles')->get();
        
        return Inertia::render('Permissions/Index', [
            'permissions' => $permissions,
        ]);
    }

    /**
     * Show the form for creating a new permission.
     */
    public function create(): Response
    {
        return Inertia::render('Permissions/Create');
    }

    /**
     * Store a newly created permission.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions',
            'description' => 'nullable|string|max:500',
        ]);

        $permission = Permission::create($validated);

        // Log permission creation
        LogService::info(LogService::CHANNEL_ROLE_PERMISSION, 'Permiso creado', [
            'permission_id' => $permission->id,
            'permission_name' => $permission->name,
            'description' => $permission->description,
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permiso creado correctamente.');
    }

    /**
     * Display the specified permission.
     */
    public function show(Permission $permission): Response
    {
        $permission->load('roles');
        $permission->loadCount('roles');
        
        return Inertia::render('Permissions/Show', [
            'permission' => $permission,
        ]);
    }

    /**
     * Show the form for editing the specified permission.
     */
    public function edit(Permission $permission): Response
    {
        $permission->load('roles');
        $permission->loadCount('roles');
        
        return Inertia::render('Permissions/Edit', [
            'permission' => $permission,
        ]);
    }

    /**
     * Update the specified permission.
     */
    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'description' => 'nullable|string|max:500',
        ]);

        // Store old values for logging
        $oldValues = [
            'name' => $permission->name,
            'description' => $permission->description,
        ];

        $permission->update($validated);

        // Log changes
        $changes = [];
        if ($oldValues['name'] !== $permission->name) {
            $changes['name'] = ['from' => $oldValues['name'], 'to' => $permission->name];
        }
        if ($oldValues['description'] !== $permission->description) {
            $changes['description'] = ['from' => $oldValues['description'], 'to' => $permission->description];
        }

        LogService::info(LogService::CHANNEL_ROLE_PERMISSION, 'Permiso actualizado', [
            'permission_id' => $permission->id,
            'permission_name' => $permission->name,
            'changes' => $changes,
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permiso actualizado correctamente.');
    }

    /**
     * Remove the specified permission.
     */
    public function destroy(Permission $permission)
    {
        // Check if permission is assigned to roles
        $rolesCount = $permission->roles()->count();
        if ($rolesCount > 0) {
            $roleNames = $permission->roles->pluck('name')->join(', ');
            return redirect()->route('permissions.index')
                ->with('error', "No se puede eliminar el permiso '{$permission->name}' porque está asignado a los roles: {$roleNames}");
        }

        // Store permission data for logging
        $permissionData = [
            'permission_id' => $permission->id,
            'permission_name' => $permission->name,
            'description' => $permission->description,
            'deleted_at' => now()->toISOString(),
        ];

        $permission->delete();

        // Log permission deletion
        LogService::warning(LogService::CHANNEL_ROLE_PERMISSION, 'Permiso eliminado', $permissionData);

        return redirect()->route('permissions.index')->with('success', 'Permiso eliminado correctamente.');
    }
}
