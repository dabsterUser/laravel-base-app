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

    protected $adminUser;
    protected $normalUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles and permissions
        $this->artisan('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);

        $this->adminUser = User::where('email', 'admin@example.com')->first();
        $this->normalUser = User::where('email', 'user@example.com')->first();
    }

    /**
     * Test that Super Admin can access the users, roles, permissions, and activity logs.
     */
    public function test_super_admin_can_access_admin_panels(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('users.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->adminUser)->get(route('roles.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->adminUser)->get(route('permissions.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->adminUser)->get(route('activity-logs.index'));
        $response->assertStatus(200);
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
     * Test user creation and automatic activity logging.
     */
    public function test_super_admin_can_create_user_and_logs_activity(): void
    {
        $roleName = 'Admin';

        $response = $this->actingAs($this->adminUser)->post(route('users.store'), [
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
            'causer_id' => $this->adminUser->id,
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
        $response = $this->actingAs($this->adminUser)->post(route('roles.store'), [
            'name' => 'Editor',
            'permissions' => ['manage users', 'view logs'],
        ]);

        $response->assertRedirect(route('roles.index'));
        $this->assertDatabaseHas('roles', ['name' => 'Editor']);

        $role = Role::where('name', 'Editor')->first();
        $this->assertTrue($role->hasPermissionTo('manage users'));
        $this->assertTrue($role->hasPermissionTo('view logs'));

        // Assert that activity log is registered
        $this->assertDatabaseHas('activity_log', [
            'causer_id' => $this->adminUser->id,
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
        $response = $this->actingAs($this->adminUser)->post(route('permissions.store'), [
            'name' => 'publish posts',
        ]);

        $response->assertRedirect(route('permissions.index'));
        $this->assertDatabaseHas('permissions', ['name' => 'publish posts']);

        $permission = Permission::where('name', 'publish posts')->first();

        // Assert that activity log is registered
        $this->assertDatabaseHas('activity_log', [
            'causer_id' => $this->adminUser->id,
            'subject_id' => $permission->id,
            'subject_type' => Permission::class,
            'description' => "Created permission 'publish posts'",
        ]);
    }
}
