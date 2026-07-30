<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin & Auditing Management Routes
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::resource('roles', \App\Http\Controllers\RoleController::class);
    Route::resource('permissions', \App\Http\Controllers\PermissionController::class);
    Route::resource('activity-logs', \App\Http\Controllers\ActivityLogController::class)->only(['index', 'show']);

    // Settings Routes
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/test-smtp', [\App\Http\Controllers\SettingsController::class, 'testSmtp'])->name('settings.test-smtp');
    Route::post('/settings/generate-email', [\App\Http\Controllers\SettingsController::class, 'generateEmail'])->name('settings.generate-email');

    // Notifications Routes
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/poll', [\App\Http\Controllers\NotificationController::class, 'poll'])->name('notifications.poll');
    Route::post('/notifications/broadcast', [\App\Http\Controllers\NotificationController::class, 'sendRoleNotification'])->name('notifications.broadcast');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');

    // Form Builder Routes
    Route::resource('forms', \App\Http\Controllers\FormBuilderController::class);
});

// Public Form Integration Routes
Route::get('/f/{id}', [\App\Http\Controllers\PublicFormController::class, 'show'])->name('public.form.show');
Route::post('/f/{id}/submit', [\App\Http\Controllers\PublicFormController::class, 'submit'])->name('public.form.submit');

require __DIR__.'/auth.php';
