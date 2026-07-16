<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('view permissions');

        $permissions = Permission::all();

        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create permissions');

        return view('admin.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create permissions');

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
        ]);

        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        activity()
            ->performedOn($permission)
            ->causedBy(auth()->user())
            ->log("Created permission '{$permission->name}'");

        return redirect()->route('permissions.index')
            ->with('status', 'permission-created')
            ->with('message', 'Permission created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        Gate::authorize('edit permissions');

        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        Gate::authorize('edit permissions');

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name,'.$permission->id],
        ]);

        $permission->name = $request->name;
        $permission->save();

        activity()
            ->performedOn($permission)
            ->causedBy(auth()->user())
            ->log("Updated permission '{$permission->name}'");

        return redirect()->route('permissions.index')
            ->with('status', 'permission-updated')
            ->with('message', 'Permission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        Gate::authorize('delete permissions');

        $name = $permission->name;
        $permission->delete();

        activity()
            ->causedBy(auth()->user())
            ->log("Deleted permission '{$name}'");

        return redirect()->route('permissions.index')
            ->with('status', 'permission-deleted')
            ->with('message', 'Permission deleted successfully.');
    }
}
