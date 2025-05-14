<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->orderBy('name')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id', // Ensure role IDs exist
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->has('roles')) {
            $roleIds = $request->input('roles', []);
            // Convert IDs to Role models
            $rolesToSync = Role::whereIn('id', $roleIds)->get();
            $user->syncRoles($rolesToSync);
        }

        // Clear cache (good practice, though less critical for user-role changes than perm changes)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load('roles', 'permissions');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        $user->load('roles');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        if ($request->has('roles')) {
            $roleIds = $request->input('roles', []);
            $rolesToSync = Role::whereIn('id', $roleIds)->get();
            $user->syncRoles($rolesToSync);
        } else {
            $user->syncRoles([]); // Remove all roles if none are selected
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete yourself.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    // --- Assigning Roles to User ---
    public function showRoles(User $user)
    {
        $roles = Role::orderBy('name')->get();
        $userRoles = $user->roles->pluck('id')->toArray(); // For pre-checking boxes
        return view('admin.users.assign-roles', compact('user', 'roles', 'userRoles'));
    }

    public function assignRoles(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $roleIds = $request->input('roles', []);
        $rolesToSync = Role::whereIn('id', $roleIds)->get();
        $user->syncRoles($rolesToSync);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('admin.users.show', $user)->with('success', 'Roles updated for ' . $user->name);
    }

    // --- (Optional) Assigning Direct Permissions to User ---
    public function showPermissions(User $user)
    {
        $permissions = Permission::orderBy('name')->get();
        $userPermissions = $user->getDirectPermissions()->pluck('id')->toArray();
        return view('admin.users.assign-permissions', compact('user', 'permissions', 'userPermissions'));
    }

    public function assignPermissions(Request $request, User $user)
    {
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $permissionIds = $request->input('permissions', []);
        // For consistency and robustness, fetch Permission models
        $permissionsToSync = Permission::whereIn('id', $permissionIds)->get();
        $user->syncPermissions($permissionsToSync);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('admin.users.show', $user)->with('success', 'Direct permissions updated for ' . $user->name);
    }
}