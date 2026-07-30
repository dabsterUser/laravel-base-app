<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class SettingsTest extends TestCase
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
     * Test that authorized users (Super Admin) can access settings index page.
     */
    public function test_super_admin_can_access_settings_index(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('settings.index'));
        $response->assertStatus(200);
        $response->assertSee('System Settings');
        $response->assertSee('SMTP Host');
    }

    /**
     * Test that unauthorized users (Standard Admin or normal User) cannot access settings.
     */
    public function test_unauthorized_users_cannot_access_settings_index(): void
    {
        // Standard admin has no manage settings permission
        $response = $this->actingAs($this->standardAdmin)->get(route('settings.index'));
        $response->assertStatus(403);

        // Normal User has no manage settings permission
        $response = $this->actingAs($this->normalUser)->get(route('settings.index'));
        $response->assertStatus(403);
    }

    /**
     * Test that Super Admin can successfully update SMTP and AI settings.
     */
    public function test_super_admin_can_update_settings(): void
    {
        $response = $this->actingAs($this->superAdmin)->post(route('settings.update'), [
            'smtp_host' => 'smtp.mailtrap.io',
            'smtp_port' => 2525,
            'smtp_username' => 'testuser',
            'smtp_password' => 'secret123',
            'smtp_encryption' => 'tls',
            'smtp_from_address' => 'noreply@mybaseapp.com',
            'smtp_from_name' => 'Base App Mailer',
            'ai_provider' => 'groq',
            'openai_api_key' => 'sk-openai-key-test',
            'groq_api_key' => 'gsk-groq-key-test',
            'anthropic_api_key' => 'sk-ant-key-test',
        ]);

        $response->assertRedirect(route('settings.index'));
        $response->assertSessionHas('message', 'Settings updated successfully.');

        // Verify keys in settings table
        $this->assertEquals('smtp.mailtrap.io', Setting::get('smtp_host'));
        $this->assertEquals(2525, Setting::get('smtp_port'));
        $this->assertEquals('groq', Setting::get('ai_provider'));
        $this->assertEquals('gsk-groq-key-test', Setting::get('groq_api_key'));

        // Assert activity log registers the change
        $this->assertDatabaseHas('activity_log', [
            'causer_id' => $this->superAdmin->id,
            'description' => "System configurations updated successfully.",
        ]);
    }

    /**
     * Test dynamic SMTP connection testing authorization and error boundary validation.
     */
    public function test_dynamic_smtp_connection_unauthorized_for_standard_user(): void
    {
        $response = $this->actingAs($this->normalUser)->post(route('settings.test-smtp'), [
            'test_email' => 'test@example.com',
            'smtp_host' => 'smtp.mailtrap.io',
            'smtp_port' => 2525,
            'smtp_from_address' => 'noreply@mybaseapp.com',
            'smtp_from_name' => 'Test',
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test that authorized users can successfully generate email templates via AI/fallback generator.
     */
    public function test_super_admin_can_generate_ai_email_template(): void
    {
        $response = $this->actingAs($this->superAdmin)->post(route('settings.generate-email'), [
            'module' => 'users',
            'tone' => 'friendly',
            'prompt' => 'Draft a welcome greeting for newly assigned staff.',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertNotNull($response->json('email'));
        $this->assertTrue(str_contains($response->json('email'), 'welcome greeting'));
    }

    /**
     * Test that parameters validation prevents bad template writing inputs.
     */
    public function test_generate_ai_email_template_validates_required_parameters(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->post(route('settings.generate-email'), [
                'module' => 'users',
                'tone' => 'friendly',
                // prompt is missing
            ]);

        $response->assertSessionHasErrors('prompt');
    }

    /**
     * Test that standard users cannot access the email generator endpoint.
     */
    public function test_generate_ai_email_template_unauthorized_for_standard_user(): void
    {
        $response = $this->actingAs($this->normalUser)->post(route('settings.generate-email'), [
            'module' => 'roles',
            'tone' => 'urgent',
            'prompt' => 'Alert the team about a password leak.',
        ]);

        $response->assertStatus(403);
    }
}
