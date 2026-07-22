<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create granular and general permissions
        $permissions = [
            // General / Broad Permissions
            'manage users',
            'manage roles',
            'manage permissions',

            // Granular Users
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Granular Roles
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',

            // Granular Permissions
            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',

            // Activity Logs
            'view logs',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // Create roles and assign permissions
        $superAdminRole = Role::findOrCreate('Super Admin', 'web');
        $superAdminRole->syncPermissions(Permission::all());

        // Standard Admin gets ONLY "manage users" and "view logs"
        // This means they will see only "Users" and "Activity Logs" in the navigation, and have no access to Roles or Permissions.
        $adminRole = Role::findOrCreate('Admin', 'web');
        $adminRole->syncPermissions([
            'manage users',
            'view logs',
        ]);

        $userRole = Role::findOrCreate('User', 'web');

        // Create Default Super Admin user
        $superAdminUser = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $superAdminUser->assignRole($superAdminRole);

        // Create Default Standard Admin user
        $adminUser = User::updateOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'Standard Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $adminUser->assignRole($adminRole);

        // Create Default Regular user
        $regularUser = User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $regularUser->assignRole($userRole);
    }
}
