<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'company_name' => 'Stark Enterprises',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        // Assert Tenant and link was created
        $this->assertDatabaseHas('tenants', [
            'name' => 'Stark Enterprises',
        ]);

        $user = \App\Models\User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user->tenant_id);
        $this->assertEquals('Stark Enterprises', $user->tenant->name);
    }
}
