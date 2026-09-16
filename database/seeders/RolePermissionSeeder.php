<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $agentRole = Role::create(['name' => 'agent']);
        $userRole = Role::create(['name' => 'user']);
        
        // Create permissions
        $permissions = [
            'view properties',
            'create properties',
            'edit properties',
            'delete properties',
            'view users',
            'edit users',
            'delete users',
        ];
        
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        
        // Assign all permissions to admin
        $adminRole->givePermissionTo(Permission::all());
        
        // Assign agent permissions
        $agentRole->givePermissionTo(['view properties', 'create properties', 'edit properties']);
        
        // Assign user permissions
        $userRole->givePermissionTo(['view properties']);
        
        // Create admin user and assign role
        $admin = User::updateOrCreate(
            ['email' => 'admin@realestate.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');
        
        // Create agent user
        $agent = User::updateOrCreate(
            ['email' => 'agent@realestate.com'],
            [
                'name' => 'John Agent',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]
        );
        $agent->assignRole('agent');
        
        // Create regular user
        $user = User::updateOrCreate(
            ['email' => 'user@realestate.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]
        );
        $user->assignRole('user');
        
        $this->command->info('Roles and permissions seeded successfully!');
    }
}