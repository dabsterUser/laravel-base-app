<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $superAdmin;
    protected $standardAdmin;
    protected $normalUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles and permissions
        $this->artisan('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);

        $this->superAdmin = User::where('email', 'admin@example.com')->first();
        $this->standardAdmin = User::where('email', 'staff@example.com')->first();
        $this->normalUser = User::where('email', 'user@example.com')->first();
    }

    /**
     * Test that Super Admin can access all panels (users, roles, permissions, activity logs).
     */
    public function test_super_admin_can_access_all_panels(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('users.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->superAdmin)->get(route('roles.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->superAdmin)->get(route('permissions.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->superAdmin)->get(route('activity-logs.index'));
        $response->assertStatus(200);
    }

    /**
     * Test that Standard Admin (with manage users and view logs) can only access those,
     * and is forbidden from roles and permissions management.
     */
    public function test_standard_admin_can_only_access_users_and_logs(): void
    {
        // Can access Users
        $response = $this->actingAs($this->standardAdmin)->get(route('users.index'));
        $response->assertStatus(200);

        // Can access Activity Logs
        $response = $this->actingAs($this->standardAdmin)->get(route('activity-logs.index'));
        $response->assertStatus(200);

        // Forbidden from Roles
        $response = $this->actingAs($this->standardAdmin)->get(route('roles.index'));
        $response->assertStatus(403);

        // Forbidden from Permissions
        $response = $this->actingAs($this->standardAdmin)->get(route('permissions.index'));
        $response->assertStatus(403);
    }

    /**
     * Test that normal users are forbidden from accessing admin panels.
     */
    public function test_normal_users_cannot_access_admin_panels(): void
    {
        $response = $this->actingAs($this->normalUser)->get(route('users.index'));
        $response->assertStatus(403);

        $response = $this->actingAs($this->normalUser)->get(route('roles.index'));
        $response->assertStatus(403);

        $response = $this->actingAs($this->normalUser)->get(route('permissions.index'));
        $response->assertStatus(403);

        $response = $this->actingAs($this->normalUser)->get(route('activity-logs.index'));
        $response->assertStatus(403);
    }

    /**
     * Test granular permission checks (e.g., standard Admin can view but cannot delete).
     */
    public function test_granular_permissions_restrict_unauthorized_actions(): void
    {
        // 1. Create a user with only "view users" permission
        $restrictedUser = User::factory()->create();
        $restrictedUser->givePermissionTo('view users');

        // Can view the list
        $response = $this->actingAs($restrictedUser)->get(route('users.index'));
        $response->assertStatus(200);

        // Cannot create users (forbidden)
        $response = $this->actingAs($restrictedUser)->get(route('users.create'));
        $response->assertStatus(403);

        $response = $this->actingAs($restrictedUser)->post(route('users.store'), [
            'name' => 'Should fail',
            'email' => 'fail@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertStatus(403);

        // Cannot delete users (forbidden)
        $response = $this->actingAs($restrictedUser)->delete(route('users.destroy', $this->normalUser));
        $response->assertStatus(403);
    }

    /**
     * Test user creation and automatic activity logging.
     */
    public function test_super_admin_can_create_user_and_logs_activity(): void
    {
        $roleName = 'Admin';

        $response = $this->actingAs($this->superAdmin)->post(route('users.store'), [
            'name' => 'New Test Admin',
            'email' => 'newadmin@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'roles' => [$roleName],
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['email' => 'newadmin@example.com']);

        $newUser = User::where('email', 'newadmin@example.com')->first();
        $this->assertTrue($newUser->hasRole($roleName));

        // Assert that activity log is registered
        $this->assertDatabaseHas('activity_log', [
            'causer_id' => $this->superAdmin->id,
            'subject_id' => $newUser->id,
            'subject_type' => User::class,
            'description' => "Created user account for {$newUser->email}",
        ]);
    }

    /**
     * Test role creation and automatic activity logging.
     */
    public function test_super_admin_can_create_role_and_logs_activity(): void
    {
        $response = $this->actingAs($this->superAdmin)->post(route('roles.store'), [
            'name' => 'Editor',
            'permissions' => ['view users', 'view logs'],
        ]);

        $response->assertRedirect(route('roles.index'));
        $this->assertDatabaseHas('roles', ['name' => 'Editor']);

        $role = Role::where('name', 'Editor')->first();
        $this->assertTrue($role->hasPermissionTo('view users'));
        $this->assertTrue($role->hasPermissionTo('view logs'));

        // Assert that activity log is registered
        $this->assertDatabaseHas('activity_log', [
            'causer_id' => $this->superAdmin->id,
            'subject_id' => $role->id,
            'subject_type' => Role::class,
            'description' => "Created role 'Editor'",
        ]);
    }

    /**
     * Test permission creation and automatic activity logging.
     */
    public function test_super_admin_can_create_permission_and_logs_activity(): void
    {
        $response = $this->actingAs($this->superAdmin)->post(route('permissions.store'), [
            'name' => 'publish posts',
        ]);

        $response->assertRedirect(route('permissions.index'));
        $this->assertDatabaseHas('permissions', ['name' => 'publish posts']);

        $permission = Permission::where('name', 'publish posts')->first();

        // Assert that activity log is registered
        $this->assertDatabaseHas('activity_log', [
            'causer_id' => $this->superAdmin->id,
            'subject_id' => $permission->id,
            'subject_type' => Permission::class,
            'description' => "Created permission 'publish posts'",
        ]);
    }
}
