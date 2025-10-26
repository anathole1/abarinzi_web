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
         // General Member Permissions
        Permission::firstOrCreate(['name' => 'complete member profile']); // Crucial for our new logic
        Permission::firstOrCreate(['name' => 'view own contributions']);
        Permission::firstOrCreate(['name' => 'submit contributions']);
        Permission::firstOrCreate(['name' => 'apply for loans']);
        Permission::firstOrCreate(['name' => 'submit loan repayments']);

        // Approval Role Permissions
        Permission::firstOrCreate(['name' => 'view pending memberships']);
        Permission::firstOrCreate(['name' => 'approve memberships']);
        Permission::firstOrCreate(['name' => 'view pending contributions']);
        Permission::firstOrCreate(['name' => 'approve contributions']);
        Permission::firstOrCreate(['name' => 'view pending loan applications']);
        Permission::firstOrCreate(['name' => 'approve loans']);
        Permission::firstOrCreate(['name' => 'confirm loan repayments']);

        // Admin Role Permissions (often has all permissions)
        Permission::firstOrCreate(['name' => 'manage all memberships']); // View all, edit, delete
        Permission::firstOrCreate(['name' => 'manage all contributions']);
        Permission::firstOrCreate(['name' => 'manage all loans']);
        Permission::firstOrCreate(['name' => 'manage users']);
        Permission::firstOrCreate(['name' => 'manage roles and permissions']);
        Permission::firstOrCreate(['name' => 'manage member categories']);
        Permission::firstOrCreate(['name' => 'manage offices']);
        Permission::firstOrCreate(['name' => 'manage website content']); // For author-like abilities
        Permission::firstOrCreate(['name' => 'generate reports']);
        
        // --- DEFINE ROLES and ASSIGN PERMISSIONS ---

        // 1. Member Role
        $memberRole = Role::firstOrCreate(['name' => 'member']);
        $memberRole->syncPermissions([
            'complete member profile',
            'view own contributions',
            'submit contributions',
            'apply for loans',
            'submit loan repayments',
        ]);

        // 2. Approval Role
        $approvalRole = Role::firstOrCreate(['name' => 'approval']);
        $approvalRole->syncPermissions([
            'view pending memberships',
            'approve memberships',
            'view pending contributions',
            'approve contributions',
            'view pending loan applications',
            'approve loans',
            'confirm loan repayments',
        ]);

        // 3. Author Role (if you keep it separate)
        $authorRole = Role::firstOrCreate(['name' => 'author']);
        $authorRole->givePermissionTo('manage website content');

        // 4. Admin Role (gets all permissions)
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());


        // --- CREATE DEMO USERS ---
        // Admin User
        $adminUser = User::firstOrCreate(
            ['email' => 'super_adm@abarinzi.org'],
            ['name' => 'Admin User', 'password' => Hash::make('P@ssw0rd807!')]
        );
        $adminUser->assignRole($adminRole);

        // Approval User
        $approvalUser = User::firstOrCreate(
            ['email' => 'aniyongana@abarinzi.org'],
            ['name' => 'Approval Officer', 'password' => Hash::make('P@ssw0rd123!')]
        );
        $approvalUser->assignRole($approvalRole);
    }
        
        // $permissions = [
        //     'view member_categories',
        //     'create member_categories',
        //     'edit member_categories',
        //     'delete member_categories',
        //     'view members',
        //     'create members',
        //     'edit members',
        //     'delete members',
        //     'approve memberships',
        //     'create users',
        //     'view users',
        //     'edit users',
        //     'delete users',
        //     'manage users', 
        //     'view contributions',
        //     'view own contributions',
        //     'create contributions',       
        //     'make contributions',   
        //     'edit contributions',
        //     'delete contributions',
        //     'manage contribution status',
        //     'generate reports',
        //     'manage loans',
        //     'create loans',
        //     'view loans',
        //     'edit loans',
        //     'delete loans',
        //     'delete contact messages'
        // ];

        // foreach ($permissions as $permission) {
        //     Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        // }

        // // Create roles and assign existing permissions
        // $adminRole = Role::firstOrCreate(['name' => 'admin']);
        // $adminRole->givePermissionTo($permissions); // Admin gets all permissions

        // $memberRole = Role::firstOrCreate(['name' => 'member']);
        // $memberRole->givePermissionTo(['view contributions', 'make contributions']); // Members can view/make contributions (once approved)

        // $authorRole = Role::firstOrCreate(['name' => 'author']);
        // $authorRole->givePermissionTo('delete contact messages');

        // // Create a demo admin user
        // $adminUser = User::firstOrCreate(
        //     ['email' => 'super_adm@abarinzi.org'],
        //     [
        //         'name' => 'Administrator',
        //         'password' => Hash::make('P@ssw0rd807!'), // Change this in a real app!
        //     ]
        // );
        // $adminUser->assignRole($adminRole);
        // $adminUser->givePermissionTo($permissions);

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
