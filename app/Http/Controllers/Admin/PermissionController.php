<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::withCount('roles')->orderBy('name')->paginate(20);
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name]);
        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully.');
    }

    // Edit for permissions is usually not recommended as names are often tied to code.
    // If you need it:
    // public function edit(Permission $permission) { /* ... */ }
    // public function update(Request $request, Permission $permission) { /* ... */ }

    public function destroy(Permission $permission)
    {
        // Check if permission is used by any roles
        if ($permission->roles()->count() > 0) {
            return redirect()->route('admin.permissions.index')->with('error', "Permission '{$permission->name}' cannot be deleted as it is assigned to roles.");
        }
        // You might also check direct user assignments if you use them extensively.
        // if (DB::table(config('permission.table_names.model_has_permissions'))->where('permission_id', $permission->id)->count() > 0) {
        // return redirect()->route('admin.permissions.index')->with('error', "Permission '{$permission->name}' cannot be deleted as it is directly assigned to users.");
        // }

        $permission->delete();
        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully.');
    }
}