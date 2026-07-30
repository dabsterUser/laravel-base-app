<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Center</span>
            <span class="text-slate-300">/</span>
            <h2 class="font-bold text-xl text-slate-800 dark:text-slate-100 leading-tight">
                Notification Center
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Messages & Success Alerts -->
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 dark:bg-emerald-950/30 dark:border-emerald-800 flex items-center space-x-3 text-emerald-800 dark:text-emerald-300">
                <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 dark:bg-rose-950/30 dark:border-rose-800 space-y-1 text-rose-800 dark:text-rose-300">
                <p class="text-sm font-semibold">There were issues with your submission:</p>
                <ul class="list-disc pl-5 text-xs space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Side: Received Notifications List (spanning 2 cols if admin can broadcast, or full if standard user) -->
            <div class="@can('manage notifications') lg:col-span-2 @else lg:col-span-3 @endcan bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col">
                <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-slate-50/50 dark:bg-slate-900/50">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Your Notifications</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Review, filter, and manage your received system alerts.</p>
                    </div>

                    <!-- Filters and Mark All Read Actions -->
                    <div class="flex flex-wrap items-center gap-2">
                        <div class="flex bg-slate-100 dark:bg-slate-800 p-0.5 rounded-lg">
                            <a href="{{ route('notifications.index', ['filter' => 'all']) }}" class="px-3 py-1 rounded-md text-xs font-semibold transition-colors {{ $filter === 'all' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200' }}">All</a>
                            <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" class="px-3 py-1 rounded-md text-xs font-semibold transition-colors {{ $filter === 'unread' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200' }}">Unread</a>
                            <a href="{{ route('notifications.index', ['filter' => 'read']) }}" class="px-3 py-1 rounded-md text-xs font-semibold transition-colors {{ $filter === 'read' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200' }}">Read</a>
                        </div>

                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-800 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-950/60 text-xs font-semibold transition-colors">
                                    Mark All Read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Notifications List Body -->
                <div class="divide-y divide-slate-100 dark:divide-slate-800 flex-1">
                    @forelse($notifications as $notification)
                        <div class="p-6 transition-colors flex items-start justify-between gap-4 {{ $notification->read_at ? 'bg-white dark:bg-slate-900 opacity-75' : 'bg-indigo-50/10 dark:bg-indigo-950/5' }}">
                            <div class="flex items-start space-x-3.5 min-w-0 flex-1">
                                <!-- Type Icon badge -->
                                @php
                                    $type = $notification->data['type'] ?? 'info';
                                    $colors = [
                                        'success' => 'bg-emerald-500/10 text-emerald-500 dark:bg-emerald-500/20',
                                        'warning' => 'bg-amber-500/10 text-amber-500 dark:bg-amber-500/20',
                                        'error' => 'bg-rose-500/10 text-rose-500 dark:bg-rose-500/20',
                                        'info' => 'bg-indigo-500/10 text-indigo-500 dark:bg-indigo-500/20',
                                    ];
                                    $colorClass = $colors[$type] ?? $colors['info'];
                                @endphp
                                <div class="h-9 w-9 rounded-xl flex items-center justify-center shrink-0 {{ $colorClass }}">
                                    @if($type === 'success')
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @elseif($type === 'warning')
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    @elseif($type === 'error')
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="text-sm font-bold text-slate-900 dark:text-white leading-5">
                                            {{ $notification->data['title'] ?? 'Notification' }}
                                        </h4>
                                        @if(!$notification->read_at)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300">
                                                New
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-1 leading-relaxed">
                                        {{ $notification->data['message'] ?? '' }}
                                    </p>
                                    @if(!empty($notification->data['link']))
                                        <a href="{{ $notification->data['link'] }}" target="_blank" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline mt-2 inline-flex items-center">
                                            <span>Visit action link</span>
                                            <svg class="h-3 w-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    @endif
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-2 block">
                                        {{ $notification->created_at->diffForHumans() }} ({{ $notification->created_at->format('M d, Y H:i') }})
                                    </span>
                                </div>
                            </div>

                            @if(!$notification->read_at)
                                <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="shrink-0">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-lg border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-semibold transition-all" title="Mark as read">
                                        Mark read
                                    </button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="py-24 text-center">
                            <div class="h-14 w-14 rounded-2xl bg-slate-50 dark:bg-slate-800 flex items-center justify-center mx-auto mb-4 text-slate-400 dark:text-slate-500">
                                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v2" />
                                </svg>
                            </div>
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white">All caught up!</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-sm mx-auto">You do not have any notifications matching this filter.</p>
                        </div>
                    @endforelse
                </div>

                @if($notifications->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>

            <!-- Right Side: Manual Broadcaster Module (visible to authorized users) -->
            @can('manage notifications')
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden h-fit">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Broadcast Alert</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Send a dynamic database notification targeting specific user roles.</p>
                    </div>

                    <form action="{{ route('notifications.broadcast') }}" method="POST" class="p-6 space-y-4">
                        @csrf

                        <!-- Target Role Selector -->
                        <div>
                            <label for="role" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Target User Role</label>
                            <select name="role" id="role" class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 py-2.5 px-3.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                                <option value="all">All Registered Users</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Notification Type -->
                        <div>
                            <label for="type" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Alert Type / Tone</label>
                            <select name="type" id="type" class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 py-2.5 px-3.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                                <option value="info">Info (Blue)</option>
                                <option value="success">Success (Green)</option>
                                <option value="warning">Warning (Yellow)</option>
                                <option value="error">Error (Red)</option>
                            </select>
                        </div>

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Notification Title</label>
                            <input type="text" name="title" id="title" required placeholder="e.g., Security Update" class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 py-2.5 px-3.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Main Message Details</label>
                            <textarea name="message" id="message" required rows="4" placeholder="Enter complete details regarding the system update..." class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 py-2.5 px-3.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"></textarea>
                        </div>

                        <!-- Action Link (Optional) -->
                        <div>
                            <label for="link" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Action Link URL (Optional)</label>
                            <input type="url" name="link" id="link" placeholder="https://example.com/dashboard" class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 py-2.5 px-3.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                        </div>

                        <!-- Dispatch Trigger Button -->
                        <button type="submit" class="w-full py-3 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs tracking-wider uppercase transition-all duration-200 shadow-md shadow-indigo-600/20 flex items-center justify-center space-x-2">
                            <svg class="h-4 w-4 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            <span>Broadcast Now</span>
                        </button>
                    </form>
                </div>
            @endcan
        </div>
    </div>
</x-app-layout>
