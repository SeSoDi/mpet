<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Services\LogService;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Users/Index', [
            'users' => User::with(['roles.permissions'])->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Users/Create', [
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

        /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'role_ids' => 'nullable|array',
            'role_ids.*' => 'exists:roles,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        // Assign roles if provided
        if (!empty($validated['role_ids'])) {
            $user->roles()->sync($validated['role_ids']);
        }

        // Log the user creation
        LogService::userAction('created', $user, [
            'email' => $user->email,
            'roles' => $user->roles->pluck('name')->toArray(),
            'created_at' => $user->created_at->toISOString(),
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with(['roles.permissions'])->findOrFail($id);
        return inertia('Users/Show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::with(['roles.permissions'])->findOrFail($id);
        return inertia('Users/Edit', [
            'user' => $user,
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        // Store old values for logging
        $oldValues = [
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->roles->pluck('name')->toArray(),
        ];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|confirmed|min:8',
            'role_ids' => 'nullable|array',
            'role_ids.*' => 'exists:roles,id',
        ]);

        // Debug logging
        \Log::info('User update request data:', [
            'role_ids' => $request->input('role_ids'),
            'validated_role_ids' => $validated['role_ids'] ?? null,
            'user_id' => $user->id,
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        // Update roles
        if (isset($validated['role_ids'])) {
            $user->roles()->sync($validated['role_ids']);
        } else {
            $user->roles()->sync([]);
        }

        // Refresh user to get updated roles
        $user->load('roles');

        // Log the user update
        $changes = [];
        if ($oldValues['name'] !== $user->name) {
            $changes['name'] = ['from' => $oldValues['name'], 'to' => $user->name];
        }
        if ($oldValues['email'] !== $user->email) {
            $changes['email'] = ['from' => $oldValues['email'], 'to' => $user->email];
        }
        if (!empty($validated['password'])) {
            $changes['password'] = 'changed';
        }
        if ($oldValues['roles'] !== $user->roles->pluck('name')->toArray()) {
            $changes['roles'] = [
                'from' => $oldValues['roles'],
                'to' => $user->roles->pluck('name')->toArray()
            ];
        }

        LogService::userAction('updated', $user, [
            'changes' => $changes,
            'updated_at' => $user->updated_at->toISOString(),
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $currentUser = auth()->user();

        // Prevent deleting the 'root' user
        if ($user->name === 'root') {
            LogService::warning(LogService::CHANNEL_USER_MANAGEMENT, 
                'Intento de eliminar usuario root bloqueado', [
                'attempted_by' => $currentUser->name ?? 'unknown',
                'target_user' => $user->name,
            ]);
            return redirect()->route('users.index')->with('error', 'No se puede eliminar el usuario root.');
        }

        // Prevent users from deleting themselves
        if ($currentUser && $user->id === $currentUser->id) {
            LogService::warning(LogService::CHANNEL_USER_MANAGEMENT, 
                'Intento de auto-eliminación bloqueado', [
                'user' => $currentUser->name,
            ]);
            return redirect()->route('users.index')->with('error', 'No puedes eliminar tu propio usuario.');
        }

        // Store user data before deletion for logging
        $userData = [
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at->toISOString(),
            'deleted_at' => now()->toISOString(),
        ];

        $user->delete();

        // Log the user deletion
        LogService::userAction('deleted', $userData['name'], $userData);

        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
