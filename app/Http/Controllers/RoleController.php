<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Gate::allows('view roles') && !Gate::allows('manage roles')) {
            abort(403, 'This action is unauthorized.');
        }

        $roles = Role::with('permissions')->get();

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Gate::allows('create roles') && !Gate::allows('manage roles')) {
            abort(403, 'This action is unauthorized.');
        }

        $permissions = Permission::all();

        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Gate::allows('create roles') && !Gate::allows('manage roles')) {
            abort(403, 'This action is unauthorized.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['nullable', 'array'],
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        if ($request->filled('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        activity()
            ->performedOn($role)
            ->causedBy(auth()->user())
            ->log("Created role '{$role->name}'");

        return redirect()->route('roles.index')
            ->with('status', 'role-created')
            ->with('message', 'Role created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        if (!Gate::allows('edit roles') && !Gate::allows('manage roles')) {
            abort(403, 'This action is unauthorized.');
        }

        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        if (!Gate::allows('edit roles') && !Gate::allows('manage roles')) {
            abort(403, 'This action is unauthorized.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,'.$role->id],
            'permissions' => ['nullable', 'array'],
        ]);

        $role->name = $request->name;
        $role->save();

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->syncPermissions([]);
        }

        activity()
            ->performedOn($role)
            ->causedBy(auth()->user())
            ->log("Updated role '{$role->name}'");

        return redirect()->route('roles.index')
            ->with('status', 'role-updated')
            ->with('message', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        if (!Gate::allows('delete roles') && !Gate::allows('manage roles')) {
            abort(403, 'This action is unauthorized.');
        }

        if ($role->name === 'Super Admin') {
            return redirect()->route('roles.index')
                ->with('error', 'The Super Admin role cannot be deleted.');
        }

        $name = $role->name;
        $role->delete();

        activity()
            ->causedBy(auth()->user())
            ->log("Deleted role '{$name}'");

        return redirect()->route('roles.index')
            ->with('status', 'role-deleted')
            ->with('message', 'Role deleted successfully.');
    }
}
