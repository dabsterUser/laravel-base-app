<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormBuilderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed permissions & roles
        $this->artisan('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
    }

    /** @test */
    public function unauthorized_user_cannot_access_form_builder_index()
    {
        $user = User::factory()->create();
        $user->assignRole('User'); // No manage forms permission

        $response = $this->actingAs($user)->get(route('forms.index'));
        $response->assertStatus(403);
    }

    /** @test */
    public function authorized_super_admin_can_access_form_builder_index()
    {
        $admin = User::whereEmail('admin@example.com')->first();

        $response = $this->actingAs($admin)->get(route('forms.index'));
        $response->assertStatus(200);
        $response->assertViewIs('forms.index');
    }

    /** @test */
    public function admin_can_create_new_form_schema()
    {
        $admin = User::whereEmail('admin@example.com')->first();

        $fieldsJson = json_encode([
            [
                'id' => 'field_1',
                'type' => 'text',
                'label' => 'Full Name',
                'name' => 'full_name',
                'placeholder' => 'Enter your full name',
                'required' => true,
            ],
            [
                'id' => 'field_2',
                'type' => 'email',
                'label' => 'Email Address',
                'name' => 'email_address',
                'placeholder' => 'Enter your email',
                'required' => false,
            ]
        ]);

        $response = $this->actingAs($admin)->post(route('forms.store'), [
            'title' => 'Contact Us Form',
            'description' => 'A dynamic customer support contact form.',
            'status' => 'active',
            'fields' => $fieldsJson,
        ]);

        $response->assertRedirect(route('forms.index'));
        $this->assertDatabaseHas('forms', [
            'title' => 'Contact Us Form',
            'status' => 'active',
        ]);

        $form = Form::whereTitle('Contact Us Form')->first();
        $this->assertCount(2, $form->fields);
        $this->assertEquals('full_name', $form->fields[0]['name']);
    }

    /** @test */
    public function guest_can_view_public_active_form()
    {
        $form = Form::create([
            'title' => 'Public Form',
            'description' => 'A public feedback form.',
            'status' => 'active',
            'fields' => [
                [
                    'id' => 'f1',
                    'type' => 'text',
                    'label' => 'Your Feedback',
                    'name' => 'feedback',
                    'required' => true,
                ]
            ],
        ]);

        $response = $this->get(route('public.form.show', $form->id));
        $response->assertStatus(200);
        $response->assertViewIs('forms.public_view');
        $response->assertSee('Public Form');
    }

    /** @test */
    public function public_form_submission_enforces_validation_rules()
    {
        $form = Form::create([
            'title' => 'Validated Form',
            'status' => 'active',
            'fields' => [
                [
                    'id' => 'f1',
                    'type' => 'email',
                    'label' => 'Primary Email',
                    'name' => 'primary_email',
                    'required' => true,
                ],
                [
                    'id' => 'f2',
                    'type' => 'number',
                    'label' => 'Age',
                    'name' => 'user_age',
                    'required' => false,
                ],
                [
                    'id' => 'f3',
                    'type' => 'checkbox',
                    'label' => 'Interests',
                    'name' => 'user_interests',
                    'required' => false,
                    'options' => ['Sports', 'Music', 'Tech'],
                ]
            ],
        ]);

        // Submit empty but required parameters - should fail validation
        $response = $this->post(route('public.form.submit', $form->id), [
            'primary_email' => '',
        ]);
        $response->assertSessionHasErrors(['primary_email']);

        // Submit invalid email and string for number - should fail
        $response = $this->post(route('public.form.submit', $form->id), [
            'primary_email' => 'invalid-email-address',
            'user_age' => 'not-a-number',
        ]);
        $response->assertSessionHasErrors(['primary_email', 'user_age']);

        // Submit valid inputs with checkboxes - should succeed
        $response = $this->post(route('public.form.submit', $form->id), [
            'primary_email' => 'customer@example.com',
            'user_age' => '25',
            'user_interests' => ['Sports', 'Tech'],
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('form_submissions', [
            'form_id' => $form->id,
        ]);

        $submission = FormSubmission::whereFormId($form->id)->first();
        $this->assertEquals('customer@example.com', $submission->data['primary_email']);
        $this->assertEquals('25', $submission->data['user_age']);
        $this->assertEquals(['Sports', 'Tech'], $submission->data['user_interests']);
    }

    /** @test */
    public function inactive_draft_form_blocks_public_view_and_submissions()
    {
        $form = Form::create([
            'title' => 'Draft Form',
            'status' => 'draft',
            'fields' => [
                ['id' => 'f1', 'type' => 'text', 'label' => 'Name', 'name' => 'fullname', 'required' => true]
            ],
        ]);

        $responseView = $this->get(route('public.form.show', $form->id));
        $responseView->assertStatus(404);

        $responseSubmit = $this->post(route('public.form.submit', $form->id), [
            'fullname' => 'John Doe',
        ]);
        $responseSubmit->assertStatus(404);
    }
}
