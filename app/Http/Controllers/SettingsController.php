<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Exception;

class SettingsController extends Controller
{
    /**
     * Display a listing of settings.
     */
    public function index()
    {
        abort_unless(Gate::allows('manage settings'), 403, 'This action is unauthorized.');

        // Load existing settings or standard defaults
        $settings = [
            'smtp_host' => Setting::get('smtp_host', config('mail.mailers.smtp.host', '127.0.0.1')),
            'smtp_port' => Setting::get('smtp_port', config('mail.mailers.smtp.port', '2525')),
            'smtp_username' => Setting::get('smtp_username', config('mail.mailers.smtp.username')),
            'smtp_password' => Setting::get('smtp_password', config('mail.mailers.smtp.password')),
            'smtp_encryption' => Setting::get('smtp_encryption', config('mail.mailers.smtp.encryption', 'tls')),
            'smtp_from_address' => Setting::get('smtp_from_address', config('mail.from.address', 'hello@example.com')),
            'smtp_from_name' => Setting::get('smtp_from_name', config('mail.from.name', 'Laravel')),

            'ai_provider' => Setting::get('ai_provider', 'openai'),
            'openai_api_key' => Setting::get('openai_api_key'),
            'groq_api_key' => Setting::get('groq_api_key'),
            'anthropic_api_key' => Setting::get('anthropic_api_key'),
        ];

        return view('settings.index', compact('settings'));
    }

    /**
     * Update system settings.
     */
    public function update(Request $request)
    {
        abort_unless(Gate::allows('manage settings'), 403, 'This action is unauthorized.');

        $validated = $request->validate([
            'smtp_host' => ['required', 'string'],
            'smtp_port' => ['required', 'integer', 'min:1', 'max:65535'],
            'smtp_username' => ['nullable', 'string'],
            'smtp_password' => ['nullable', 'string'],
            'smtp_encryption' => ['nullable', 'string'],
            'smtp_from_address' => ['required', 'email'],
            'smtp_from_name' => ['required', 'string'],
            'ai_provider' => ['required', 'string', 'in:openai,groq,anthropic'],
            'openai_api_key' => ['nullable', 'string'],
            'groq_api_key' => ['nullable', 'string'],
            'anthropic_api_key' => ['nullable', 'string'],
        ]);

        // Save keys
        Setting::setMany($validated);

        // Explicit general log
        activity()
            ->causedBy(auth()->user())
            ->log("System configurations updated successfully.");

        return redirect()->route('settings.index')
            ->with('status', 'settings-updated')
            ->with('message', 'Settings updated successfully.');
    }

    /**
     * Send a test SMTP email dynamically using the supplied settings.
     */
    public function testSmtp(Request $request)
    {
        abort_unless(Gate::allows('manage settings'), 403, 'This action is unauthorized.');

        $request->validate([
            'test_email' => ['required', 'email'],
            'smtp_host' => ['required', 'string'],
            'smtp_port' => ['required', 'integer'],
            'smtp_username' => ['nullable', 'string'],
            'smtp_password' => ['nullable', 'string'],
            'smtp_encryption' => ['nullable', 'string'],
            'smtp_from_address' => ['required', 'email'],
            'smtp_from_name' => ['required', 'string'],
        ]);

        try {
            // Apply Dynamic Mail Config in memory
            Config::set('mail.mailers.smtp.transport', 'smtp');
            Config::set('mail.mailers.smtp.host', $request->smtp_host);
            Config::set('mail.mailers.smtp.port', $request->smtp_port);
            Config::set('mail.mailers.smtp.username', $request->smtp_username);
            Config::set('mail.mailers.smtp.password', $request->smtp_password);
            Config::set('mail.mailers.smtp.encryption', $request->smtp_encryption === 'none' ? null : $request->smtp_encryption);
            Config::set('mail.from.address', $request->smtp_from_address);
            Config::set('mail.from.name', $request->smtp_from_name);

            // Forget cached mailer instance to force re-instantiation
            app()->get('mail.manager')->forgetMailers();

            // Send test email
            Mail::raw("Dynamic SMTP Server Connection Test Successful!\n\nThis message was sent from your Laravel Base Application to verify that the SMTP settings configured in your Settings module are correct.", function ($message) use ($request) {
                $message->to($request->test_email)
                        ->subject("SMTP Test Connection: Successful");
            });

            // Log successful test activity
            activity()
                ->causedBy(auth()->user())
                ->log("Executed dynamic SMTP mail connection test to: {$request->test_email}. Result: SUCCESS.");

            return redirect()->route('settings.index')
                ->with('status', 'smtp-success')
                ->with('message', "SMTP Connection success! A test email has been successfully sent to {$request->test_email}.");

        } catch (Exception $e) {
            // Log failed test activity
            activity()
                ->causedBy(auth()->user())
                ->log("Executed dynamic SMTP mail connection test to: {$request->test_email}. Result: FAILED with error: " . $e->getMessage());

            return redirect()->route('settings.index')
                ->withInput()
                ->with('error', "SMTP Connection failed: " . $e->getMessage());
        }
    }
}
