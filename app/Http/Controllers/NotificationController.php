<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications and the dispatch form.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->query('filter', 'all');

        $query = $user->notifications();

        if ($filter === 'unread') {
            $query->unread();
        } elseif ($filter === 'read') {
            $query->read();
        }

        $notifications = $query->paginate(15)->withQueryString();
        $roles = Role::all();

        return view('notifications.index', compact('notifications', 'roles', 'filter'));
    }

    /**
     * Dispatch a manual notification targeting standard roles.
     */
    public function sendRoleNotification(Request $request)
    {
        if (!Gate::allows('manage notifications')) {
            abort(403, 'This action is unauthorized.');
        }

        $validated = $request->validate([
            'role' => 'required|string',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|string|in:info,success,warning,error',
            'link' => 'nullable|url|max:255',
        ]);

        $usersQuery = User::query();

        if ($validated['role'] !== 'all') {
            $usersQuery->role($validated['role']);
        }

        $users = $usersQuery->get();

        foreach ($users as $user) {
            $user->notify(new SystemNotification(
                $validated['title'],
                $validated['message'],
                $validated['type'],
                $validated['link']
            ));
        }

        activity()
            ->causedBy(Auth::user())
            ->withProperties(['role' => $validated['role'], 'title' => $validated['title']])
            ->log("Broadcasted role-based notification targeting: " . $validated['role']);

        return redirect()->back()->with('success', 'Broadcasted notification successfully to targeted role(s).');
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all unread notifications of the current user as read.
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
