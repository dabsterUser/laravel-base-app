<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
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

    /**
     * Generate an email template dynamically using either configured active AI,
     * or a fallback professional context-aware AI email generator.
     */
    public function generateEmail(Request $request)
    {
        abort_unless(Gate::allows('manage settings'), 403, 'This action is unauthorized.');

        $request->validate([
            'module' => ['required', 'string', 'in:users,roles,activity_logs,custom'],
            'tone' => ['required', 'string', 'in:professional,friendly,urgent,persuasive'],
            'prompt' => ['required', 'string', 'max:1000'],
        ]);

        $module = $request->module;
        $tone = $request->tone;
        $prompt = $request->prompt;

        // Retrieve Active AI configuration
        $provider = Setting::get('ai_provider', 'openai');
        $apiKey = null;

        if ($provider === 'openai') {
            $apiKey = Setting::get('openai_api_key');
        } elseif ($provider === 'groq') {
            $apiKey = Setting::get('groq_api_key');
        } elseif ($provider === 'anthropic') {
            $apiKey = Setting::get('anthropic_api_key');
        }

        $promptText = "Generate a beautifully styled email template about the Laravel application module: '{$module}'. "
            . "The tone of voice must be '{$tone}'. "
            . "Specific instructions/topic details: '{$prompt}'. "
            . "Please provide a clear Subject line and a formatted email body. "
            . "Do not write any preamble, surrounding quotes, or explanations—just the subject and the email body itself.";

        if (!empty($apiKey)) {
            try {
                if ($provider === 'openai') {
                    $response = Http::withHeaders([
                        'Authorization' => "Bearer {$apiKey}",
                    ])->timeout(15)->post('https://api.openai.com/v1/chat/completions', [
                        'model' => 'gpt-3.5-turbo',
                        'messages' => [
                            ['role' => 'system', 'content' => 'You are an elite AI email writer that outputs clean, professional text email templates.'],
                            ['role' => 'user', 'content' => $promptText],
                        ],
                    ]);

                    if ($response->successful()) {
                        $text = $response->json('choices.0.message.content');
                        if (!empty($text)) {
                            return response()->json(['success' => true, 'email' => trim($text)]);
                        }
                    }
                } elseif ($provider === 'groq') {
                    $response = Http::withHeaders([
                        'Authorization' => "Bearer {$apiKey}",
                    ])->timeout(15)->post('https://api.groq.com/openai/v1/chat/completions', [
                        'model' => 'llama3-8b-8192',
                        'messages' => [
                            ['role' => 'system', 'content' => 'You are an elite AI email writer that outputs clean, professional text email templates.'],
                            ['role' => 'user', 'content' => $promptText],
                        ],
                    ]);

                    if ($response->successful()) {
                        $text = $response->json('choices.0.message.content');
                        if (!empty($text)) {
                            return response()->json(['success' => true, 'email' => trim($text)]);
                        }
                    }
                } elseif ($provider === 'anthropic') {
                    $response = Http::withHeaders([
                        'x-api-key' => $apiKey,
                        'anthropic-version' => '2023-06-01',
                        'content-type' => 'application/json',
                    ])->timeout(15)->post('https://api.anthropic.com/v1/messages', [
                        'model' => 'claude-3-haiku-20240307',
                        'max_tokens' => 1024,
                        'messages' => [
                            ['role' => 'user', 'content' => $promptText],
                        ],
                    ]);

                    if ($response->successful()) {
                        $text = $response->json('content.0.text');
                        if (!empty($text)) {
                            return response()->json(['success' => true, 'email' => trim($text)]);
                        }
                    }
                }
            } catch (Exception $e) {
                // Fallback gracefully on exceptions
            }
        }

        // Fallback context-aware intelligent simulation generator
        $subject = "System Notification: Update concerning your " . ucfirst($module) . " activity";
        $salutation = "Dear Administrator,";

        if ($tone === 'friendly') {
            $subject = "Hey there! Quick update on your " . ucfirst($module);
            $salutation = "Hi team,";
        } elseif ($tone === 'urgent') {
            $subject = "URGENT ACTION REQUIRED: Critical " . ucfirst($module) . " Update Required";
            $salutation = "ATTENTION: Administrative Team,";
        } elseif ($tone === 'persuasive') {
            $subject = "Why you should check out the latest changes on our " . ucfirst($module) . " dashboard";
            $salutation = "Dear valued user,";
        }

        // Custom details matching modules
        $body = "We wanted to reach out regarding your recent configurations in the " . ucfirst($module) . " module.\n\n";
        if ($module === 'users') {
            $body .= "A user account modification has occurred. Please confirm that all newly assigned security roles conform to your organization's compliance standard.\n";
        } elseif ($module === 'roles') {
            $body .= "New system permission policies have been established. This change modifies resource access controls for several user groups across the platform.\n";
        } elseif ($module === 'activity_logs') {
            $body .= "Our audit logs tracking has logged some high-privilege activities. We recommend verifying the activity feed to ensure all actions match authorized actions.\n";
        } else {
            $body .= "Your system parameters have been successfully processed. The dashboard analytics have been compiled and updated in real-time.\n";
        }

        $body .= "\nAdditional Details / User Request:\n\"" . $prompt . "\"\n\n";
        $body .= "Should you have any questions or require additional assistance, please reach out to our dynamic support helpdesk.\n\nBest regards,\nYour System Operations Bot";

        $fullFallbackEmail = "Subject: " . $subject . "\n\n" . $salutation . "\n\n" . $body;

        return response()->json([
            'success' => true,
            'email' => $fullFallbackEmail,
            'fallback' => true, // Flag indicating mock/fallback execution was performed
        ]);
    }
}
