<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class FormBuilderController extends Controller
{
    /**
     * Display a listing of forms.
     */
    public function index()
    {
        if (!Gate::allows('manage forms')) {
            abort(403, 'This action is unauthorized.');
        }

        $forms = Form::withCount('submissions')->latest()->paginate(10);

        return view('forms.index', compact('forms'));
    }

    /**
     * Show the form for creating a new dynamic form.
     */
    public function create()
    {
        if (!Gate::allows('manage forms')) {
            abort(403, 'This action is unauthorized.');
        }

        return view('forms.create');
    }

    /**
     * Store a newly created dynamic form.
     */
    public function store(Request $request)
    {
        if (!Gate::allows('manage forms')) {
            abort(403, 'This action is unauthorized.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|in:active,draft',
            'fields' => 'nullable|string', // Expecting JSON string from builder
        ]);

        $fieldsArray = [];
        if ($request->filled('fields')) {
            $fieldsArray = json_decode($request->fields, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return redirect()->back()->withErrors(['fields' => 'Invalid fields JSON format.'])->withInput();
            }
        }

        $form = Form::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'fields' => $fieldsArray,
            'user_id' => Auth::id(),
        ]);

        activity()
            ->performedOn($form)
            ->causedBy(Auth::user())
            ->log("Created dynamic form: " . $form->title);

        return redirect()->route('forms.index')->with('success', 'Form created successfully.');
    }

    /**
     * Display the form details, shortcodes, integration snippets, and its submissions.
     */
    public function show($id)
    {
        if (!Gate::allows('manage forms')) {
            abort(403, 'This action is unauthorized.');
        }

        $form = Form::with(['submissions' => function ($query) {
            $query->latest();
        }])->findOrFail($id);

        return view('forms.show', compact('form'));
    }

    /**
     * Show the editor for updating a dynamic form.
     */
    public function edit($id)
    {
        if (!Gate::allows('manage forms')) {
            abort(403, 'This action is unauthorized.');
        }

        $form = Form::findOrFail($id);

        return view('forms.edit', compact('form'));
    }

    /**
     * Update the dynamic form.
     */
    public function update(Request $request, $id)
    {
        if (!Gate::allows('manage forms')) {
            abort(403, 'This action is unauthorized.');
        }

        $form = Form::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|in:active,draft',
            'fields' => 'nullable|string',
        ]);

        $fieldsArray = [];
        if ($request->filled('fields')) {
            $fieldsArray = json_decode($request->fields, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return redirect()->back()->withErrors(['fields' => 'Invalid fields JSON format.'])->withInput();
            }
        }

        $form->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'fields' => $fieldsArray,
        ]);

        activity()
            ->performedOn($form)
            ->causedBy(Auth::user())
            ->log("Updated dynamic form: " . $form->title);

        return redirect()->route('forms.index')->with('success', 'Form updated successfully.');
    }

    /**
     * Delete the dynamic form.
     */
    public function destroy($id)
    {
        if (!Gate::allows('manage forms')) {
            abort(403, 'This action is unauthorized.');
        }

        $form = Form::findOrFail($id);
        $title = $form->title;
        $form->delete();

        activity()
            ->causedBy(Auth::user())
            ->log("Deleted dynamic form: " . $title);

        return redirect()->route('forms.index')->with('success', 'Form deleted successfully.');
    }
}
