<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $permissions = [
            'view member_categories',
            'create member_categories',
            'edit member_categories',
            'delete member_categories',
            'view members',
            'create members',
            'edit members',
            'delete members',
            'approve memberships',
            'manage users', 
            'view contributions',
            'view own contributions',
            'create contributions',       
            'make contributions',   
            'edit contributions',
            'delete contributions',
            'manage contribution status',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles and assign existing permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo($permissions); // Admin gets all permissions

        $memberRole = Role::firstOrCreate(['name' => 'member']);
        $memberRole->givePermissionTo(['view contributions', 'make contributions']); // Members can view/make contributions (once approved)


        // Create a demo admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@abarinzifamily.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('p@ssw0rd123'), // Change this in a real app!
            ]
        );
        $adminUser->assignRole($adminRole);
        $adminUser->givePermissionTo($permissions);

        // Create a demo member user (for testing, will be assigned role on registration)
        // $memberUser = User::firstOrCreate(
        //     ['email' => 'member@efotec.com'],
        //     [
        //         'name' => 'Test Member',
        //         'password' => bcrypt('password'),
        //     ]
        // );
        // $memberUser->assignRole($memberRole);
    }
}