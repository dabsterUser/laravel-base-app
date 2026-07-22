<?php

namespace App\Http\Controllers;

use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('view logs');

        $logs = Activity::with(['causer', 'subject'])->latest()->paginate(25);

        return view('admin.activity-logs.index', compact('logs'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activityLog)
    {
        Gate::authorize('view logs');

        return view('admin.activity-logs.show', ['log' => $activityLog]);
    }
}
