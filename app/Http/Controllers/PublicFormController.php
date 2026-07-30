<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublicFormController extends Controller
{
    /**
     * Render the public form for embedding or viewing directly.
     */
    public function show($id)
    {
        $form = Form::findOrFail($id);

        if ($form->status !== 'active') {
            abort(404, 'This form is currently not accepting submissions.');
        }

        return view('forms.public_view', compact('form'));
    }

    /**
     * Handle public form submissions with dynamic rule validation.
     */
    public function submit(Request $request, $id)
    {
        $form = Form::findOrFail($id);

        if ($form->status !== 'active') {
            abort(404, 'This form is currently not accepting submissions.');
        }

        $fields = $form->fields ?? [];
        $rules = [];
        $attributes = [];

        foreach ($fields as $field) {
            $name = $field['name'] ?? null;
            if (!$name) {
                continue;
            }

            $fieldRules = [];

            // Required validation
            if (!empty($field['required']) && $field['required'] === true) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            // Type validation
            $type = $field['type'] ?? 'text';
            if ($type === 'email') {
                $fieldRules[] = 'email';
            } elseif ($type === 'number') {
                $fieldRules[] = 'numeric';
            } elseif ($type === 'checkbox') {
                $fieldRules[] = 'array';
            } else {
                $fieldRules[] = 'string';
            }

            $rules[$name] = $fieldRules;
            $attributes[$name] = $field['label'] ?? $name;
        }

        // Validate the request inputs
        $validator = Validator::make($request->all(), $rules, [], $attributes);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Filter and collect fields data
        $submissionData = [];
        foreach ($fields as $field) {
            $name = $field['name'] ?? null;
            if ($name) {
                $submissionData[$name] = $request->input($name);
            }
        }

        // Create submission
        $submission = FormSubmission::create([
            'form_id' => $form->id,
            'data' => $submissionData,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'is_read' => false,
        ]);

        // Dispatch system notifications to appropriate users
        try {
            $recipients = collect();

            // 1. Add form creator if they exist
            if ($form->user_id) {
                $creator = \App\Models\User::find($form->user_id);
                if ($creator) {
                    $recipients->push($creator);
                }
            }

            // 2. Add any user who has 'manage forms' permission
            $admins = \App\Models\User::permission('manage forms')->get();
            foreach ($admins as $admin) {
                $recipients->push($admin);
            }

            // De-duplicate recipients by ID
            $recipients = $recipients->unique('id');

            $notificationTitle = "New Submission: " . $form->title;
            $notificationMsg = "A new submission was received on " . $form->title . " from IP " . $request->ip() . ".";
            $notificationLink = route('forms.show', $form->id);

            foreach ($recipients as $recipient) {
                $recipient->notify(new \App\Notifications\SystemNotification(
                    $notificationTitle,
                    $notificationMsg,
                    'info',
                    $notificationLink
                ));
            }
        } catch (\Exception $e) {
            // Silently fallback if anything fails during notification dispatching
            logger()->error('Form submission notification error: ' . $e->getMessage());
        }

        // Trigger log activity
        activity()
            ->performedOn($submission)
            ->withProperties(['form_title' => $form->title])
            ->log("New submission received for form: " . $form->title);

        return redirect()->back()->with('success', 'Thank you! Your submission has been successfully received.');
    }
}
