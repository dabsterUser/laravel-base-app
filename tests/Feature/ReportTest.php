<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Form;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Register default Spatie Role
        Role::findOrCreate('Super Admin', 'web');
    }

    /** @test */
    public function authenticated_user_can_access_reports_center()
    {
        $tenant = Tenant::create(['name' => 'Acme Inc', 'slug' => 'acme']);
        $user = User::create([
            'name' => 'John Reporter',
            'email' => 'john@acme.com',
            'password' => bcrypt('password'),
            'tenant_id' => $tenant->id,
        ]);
        $user->assignRole('Super Admin');

        $response = $this->actingAs($user)->get('/reports');

        $response->assertStatus(200);
        $response->assertSee('Dynamic Reporting Center');
        $response->assertSee('User');
    }

    /** @test */
    public function user_can_get_columns_of_a_module_dynamically()
    {
        $tenant = Tenant::create(['name' => 'Acme Inc', 'slug' => 'acme']);
        $user = User::create([
            'name' => 'John Reporter',
            'email' => 'john@acme.com',
            'password' => bcrypt('password'),
            'tenant_id' => $tenant->id,
        ]);

        $response = $this->actingAs($user)->getJson('/reports/columns?model=User');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'columns',
            'table'
        ]);
        $response->assertJsonFragment(['table' => 'users']);
    }

    /** @test */
    public function user_export_is_strictly_tenant_scoped()
    {
        // Create two tenants
        $tenant1 = Tenant::create(['name' => 'Acme Inc', 'slug' => 'acme']);
        $tenant2 = Tenant::create(['name' => 'Stark Industries', 'slug' => 'stark']);

        // User in tenant 1
        $user1 = User::create([
            'name' => 'Tony Stark',
            'email' => 'tony@stark.com',
            'password' => bcrypt('password'),
            'tenant_id' => $tenant2->id,
        ]);

        // Create a dummy user in tenant1
        $userAcme = User::create([
            'name' => 'Acme User',
            'email' => 'acme@acme.com',
            'password' => bcrypt('password'),
            'tenant_id' => $tenant1->id,
        ]);

        // Create forms in each tenant
        $form1 = Form::create([
            'title' => 'Form for Acme',
            'description' => 'Test',
            'fields' => [],
            'status' => 'published',
            'tenant_id' => $tenant1->id,
            'user_id' => $userAcme->id
        ]);

        $form2 = Form::create([
            'title' => 'Form for Stark',
            'description' => 'Top Secret',
            'fields' => [],
            'status' => 'published',
            'tenant_id' => $tenant2->id,
            'user_id' => $user1->id
        ]);

        // When Tony (tenant 2) generates print report of forms
        $response = $this->actingAs($user1)->get('/reports/export?' . http_build_query([
            'model' => 'Form',
            'columns' => ['title', 'description'],
            'format' => 'print',
        ]));

        $response->assertStatus(200);
        // Tony must see Stark's form
        $response->assertSee('Form for Stark');
        // Tony must NOT see Acme's form due to tenant isolation!
        $response->assertDontSee('Form for Acme');
    }

    /** @test */
    public function user_can_export_csv_report()
    {
        $tenant = Tenant::create(['name' => 'Acme Inc', 'slug' => 'acme']);
        $user = User::create([
            'name' => 'John Reporter',
            'email' => 'john@acme.com',
            'password' => bcrypt('password'),
            'tenant_id' => $tenant->id,
        ]);

        $response = $this->actingAs($user)->get('/reports/export?' . http_build_query([
            'model' => 'User',
            'columns' => ['name', 'email'],
            'format' => 'csv',
        ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $content = $response->streamedContent();
        $this->assertStringContainsString('John Reporter', $content);
        $this->assertStringContainsString('john@acme.com', $content);
    }
}
