<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $lawyerRole = Role::create(['name' => 'lawyer']);
        $assistantRole = Role::create(['name' => 'assistant']);

        // Create permissions
        $permissions = [
            'view_reports',
            'create_reports',
            'edit_reports',
            'delete_reports',
            'manage_users',
            'view_dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Give all permissions to admin
        $adminRole->givePermissionTo(Permission::all());
        
        // Give specific permissions to lawyer
        $lawyerRole->givePermissionTo([
            'view_reports',
            'create_reports',
            'edit_reports',
            'view_dashboard',
        ]);

        // Give specific permissions to assistant
        $assistantRole->givePermissionTo([
            'view_reports',
            'view_dashboard',
        ]);

        // Assign admin role to admin user
        $admin = User::where('email', 'admin@example.com')->first();
        if ($admin) {
            $admin->assignRole('admin');
        }
    }
}
