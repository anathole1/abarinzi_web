<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission; // Ensure this is imported

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users', 'permissions')->orderBy('name')->paginate(15);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('name')->get();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id', // Validates IDs exist
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->has('permissions')) {
            $permissionIds = $request->input('permissions', []);
            // Convert IDs to Permission models or names
            // Option 1: Using Permission Models (Most Robust)
            $permissionsToSync = Permission::whereIn('id', $permissionIds)->get();
            $role->syncPermissions($permissionsToSync);

            // Option 2: Using Permission Names (Also good)
            // $permissionNames = Permission::whereIn('id', $permissionIds)->pluck('name')->toArray();
            // $role->syncPermissions($permissionNames);
        }

        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function show(Role $role)
    {
        $role->load('permissions', 'users');
        return view('admin.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        if (in_array($role->name, ['admin', 'member'])) {
             // Consider a more user-friendly way to handle this if needed
        }
        $permissions = Permission::orderBy('name')->get();
        $rolePermissions = $role->permissions->pluck('id')->toArray(); // Used to pre-check boxes
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        if (in_array($role->name, ['admin', 'member']) && $role->name !== $request->name) {
             return redirect()->route('admin.roles.index')->with('error', "Cannot change the name of the core '{$role->name}' role.");
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->name = $request->name;
        $role->save();

        if ($request->has('permissions')) {
            $permissionIds = $request->input('permissions', []);
            $permissionsToSync = Permission::whereIn('id', $permissionIds)->get();
            $role->syncPermissions($permissionsToSync);
        } else {
            $role->syncPermissions([]); // Remove all permissions
        }

        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if (in_array($role->name, ['admin', 'member'])) {
            return redirect()->route('admin.roles.index')->with('error', "Core role '{$role->name}' cannot be deleted.");
        }
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')->with('error', "Role '{$role->name}' cannot be deleted as it is assigned to users.");
        }
        $role->delete();

        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }

    // --- Assigning Permissions to Role ---
    public function showPermissions(Role $role)
    {
        $permissions = Permission::orderBy('name')->get();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('admin.roles.assign-permissions', compact('role', 'permissions', 'rolePermissions'));
    }

    public function assignPermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $permissionIds = $request->input('permissions', []);
        $permissionsToSync = Permission::whereIn('id', $permissionIds)->get();
        $role->syncPermissions($permissionsToSync);

        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('admin.roles.show', $role)->with('success', 'Permissions updated for role ' . $role->name);
    }
}