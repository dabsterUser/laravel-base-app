<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Notifications\SystemNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed Roles and Permissions
        $this->artisan('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
    }

    /** @test */
    public function authenticated_user_can_access_notifications_center()
    {
        $user = User::factory()->create();
        $user->assignRole('User');

        $response = $this->actingAs($user)->get(route('notifications.index'));

        $response->assertStatus(200);
        $response->assertViewIs('notifications.index');
    }

    /** @test */
    public function unauthorized_user_cannot_broadcast_role_notifications()
    {
        $user = User::factory()->create();
        $user->assignRole('User');

        $response = $this->actingAs($user)->post(route('notifications.broadcast'), [
            'role' => 'User',
            'title' => 'Unpermitted Notice',
            'message' => 'Should fail due to lack of manage notifications permission',
            'type' => 'warning',
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function authorized_super_admin_can_broadcast_notifications_to_specific_role()
    {
        $admin = User::whereEmail('admin@example.com')->first();
        $user1 = User::factory()->create();
        $user1->assignRole('User');
        $user2 = User::factory()->create();
        $user2->assignRole('User');

        $this->assertCount(0, $user1->unreadNotifications);
        $this->assertCount(0, $user2->unreadNotifications);

        $response = $this->actingAs($admin)->post(route('notifications.broadcast'), [
            'role' => 'User',
            'title' => 'Attention All Users',
            'message' => 'Please update your security profile immediately.',
            'type' => 'success',
            'link' => 'https://example.com/profile',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify notification received
        $user1->refresh();
        $user2->refresh();

        $this->assertCount(1, $user1->unreadNotifications);
        $this->assertCount(1, $user2->unreadNotifications);

        $notifData = $user1->unreadNotifications->first()->data;
        $this->assertEquals('Attention All Users', $notifData['title']);
        $this->assertEquals('success', $notifData['type']);
        $this->assertEquals('https://example.com/profile', $notifData['link']);
    }

    /** @test */
    public function user_can_mark_notification_as_read()
    {
        $user = User::factory()->create();
        $user->assignRole('User');

        $user->notify(new SystemNotification('Test Title', 'Test Message', 'info'));
        $this->assertCount(1, $user->unreadNotifications);

        $notificationId = $user->unreadNotifications->first()->id;

        $response = $this->actingAs($user)->post(route('notifications.read', $notificationId));

        $response->assertRedirect();
        $user->refresh();
        $this->assertCount(0, $user->unreadNotifications);
    }

    /** @test */
    public function user_can_mark_all_notifications_as_read()
    {
        $user = User::factory()->create();
        $user->assignRole('User');

        $user->notify(new SystemNotification('First', 'First details', 'info'));
        $user->notify(new SystemNotification('Second', 'Second details', 'info'));
        $this->assertCount(2, $user->unreadNotifications);

        $response = $this->actingAs($user)->post(route('notifications.mark-all-read'));

        $response->assertRedirect();
        $user->refresh();
        $this->assertCount(0, $user->unreadNotifications);
    }
}
