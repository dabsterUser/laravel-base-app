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

        // Create permissions
        $permissions = [
            'manage users',
            'manage roles',
            'manage permissions',
            'view logs',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // Create roles and assign created permissions
        $superAdminRole = Role::findOrCreate('Super Admin', 'web');
        // Super Admin gets all permissions via Gate::before in AuthServiceProvider or AppServiceProvider, but let's sync them anyway
        $superAdminRole->syncPermissions(Permission::all());

        $adminRole = Role::findOrCreate('Admin', 'web');
        $adminRole->syncPermissions(['manage users', 'view logs']);

        $userRole = Role::findOrCreate('User', 'web');
        // Regular User doesn't have administrative permissions by default

        // Create Default Super Admin user
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $adminUser->assignRole($superAdminRole);

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
