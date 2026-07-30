<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    /**
     * Check if the authenticated user is a Global Admin (has no tenant_id limit).
     */
    protected function authorizeGlobalAdmin()
    {
        if (auth()->user()->tenant_id !== null) {
            abort(403, 'Unauthorized. Only Global Admins can access tenant management.');
        }
    }

    /**
     * Display a listing of tenants.
     */
    public function index()
    {
        $this->authorizeGlobalAdmin();

        $tenants = Tenant::withCount(['users', 'forms'])->get();

        return view('admin.tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new tenant.
     */
    public function create()
    {
        $this->authorizeGlobalAdmin();

        return view('admin.tenants.create');
    }

    /**
     * Store a newly created tenant.
     */
    public function store(Request $request)
    {
        $this->authorizeGlobalAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tenants,slug',
        ]);

        Tenant::create([
            'name' => $request->name,
            'slug' => $request->slug ? Str::slug($request->slug) : Str::slug($request->name),
        ]);

        return redirect()->route('tenants.index')->with('success', 'Tenant created successfully!');
    }

    /**
     * Display the specified tenant's details.
     */
    public function show($id)
    {
        $this->authorizeGlobalAdmin();

        $tenant = Tenant::with(['users', 'forms'])->findOrFail($id);

        return view('admin.tenants.show', compact('tenant'));
    }

    /**
     * Remove the specified tenant.
     */
    public function destroy($id)
    {
        $this->authorizeGlobalAdmin();

        $tenant = Tenant::findOrFail($id);
        $tenant->delete();

        return redirect()->route('tenants.index')->with('success', 'Tenant deleted successfully!');
    }
}
