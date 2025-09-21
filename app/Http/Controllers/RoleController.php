<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Services\LogService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index(): Response
    {
        $roles = Role::with('permissions')->withCount('users')->get();
        
        return Inertia::render('Roles/Index', [
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for creating a new role.
     */
    public function create(): Response
    {
        $permissions = Permission::all();
        
        return Inertia::render('Roles/Create', [
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        // Attach permissions if provided
        if (!empty($validated['permissions'])) {
            $role->permissions()->attach($validated['permissions']);
        }

        // Log role creation
        LogService::info(LogService::CHANNEL_ROLE_PERMISSION, 'Rol creado', [
            'role_id' => $role->id,
            'role_name' => $role->name,
            'description' => $role->description,
            'permissions_assigned' => count($validated['permissions'] ?? []),
            'permission_ids' => $validated['permissions'] ?? [],
        ]);

        return redirect()->route('roles.index')->with('success', 'Rol creado correctamente.');
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role): Response
    {
        $role->load('permissions', 'users');
        $role->loadCount('users');
        
        return Inertia::render('Roles/Show', [
            'role' => $role,
        ]);
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role): Response
    {
        $role->load('permissions');
        $permissions = Permission::all();
        
        return Inertia::render('Roles/Edit', [
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Store old values for logging
        $oldValues = [
            'name' => $role->name,
            'description' => $role->description,
            'permission_ids' => $role->permissions->pluck('id')->toArray(),
        ];

        $role->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        // Sync permissions
        $role->permissions()->sync($validated['permissions'] ?? []);

        // Log changes
        $changes = [];
        if ($oldValues['name'] !== $role->name) {
            $changes['name'] = ['from' => $oldValues['name'], 'to' => $role->name];
        }
        if ($oldValues['description'] !== $role->description) {
            $changes['description'] = ['from' => $oldValues['description'], 'to' => $role->description];
        }
        
        $newPermissionIds = $validated['permissions'] ?? [];
        if ($oldValues['permission_ids'] !== $newPermissionIds) {
            $changes['permissions'] = [
                'from' => $oldValues['permission_ids'],
                'to' => $newPermissionIds,
                'added' => array_diff($newPermissionIds, $oldValues['permission_ids']),
                'removed' => array_diff($oldValues['permission_ids'], $newPermissionIds),
            ];
        }

        LogService::info(LogService::CHANNEL_ROLE_PERMISSION, 'Rol actualizado', [
            'role_id' => $role->id,
            'role_name' => $role->name,
            'changes' => $changes,
        ]);

        return redirect()->route('roles.index')->with('success', 'Rol actualizado correctamente.');
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Role $role)
    {
        // Prevent deletion of essential roles
        $protectedRoles = ['superadmin', 'admin'];
        if (in_array($role->name, $protectedRoles)) {
            return redirect()->route('roles.index')
                ->with('error', 'No se puede eliminar el rol ' . $role->name . ' porque es un rol del sistema.');
        }

        // Check if role is assigned to users
        $usersCount = $role->users()->count();
        if ($usersCount > 0) {
            return redirect()->route('roles.index')
                ->with('error', "No se puede eliminar el rol '{$role->name}' porque está asignado a {$usersCount} usuario(s).");
        }

        // Store role data for logging
        $roleData = [
            'role_id' => $role->id,
            'role_name' => $role->name,
            'description' => $role->description,
            'permissions' => $role->permissions->pluck('name')->toArray(),
            'deleted_at' => now()->toISOString(),
        ];

        $role->delete();

        // Log role deletion
        LogService::warning(LogService::CHANNEL_ROLE_PERMISSION, 'Rol eliminado', $roleData);

        return redirect()->route('roles.index')->with('success', 'Rol eliminado correctamente.');
    }
}
