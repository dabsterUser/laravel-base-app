<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 dark:bg-slate-950">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased h-full text-slate-900 dark:text-slate-100" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen flex bg-slate-50 dark:bg-slate-950">
            <!-- Sidebar Backdrop for Mobile -->
            <div
                x-show="sidebarOpen"
                @click="sidebarOpen = false"
                x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm md:hidden"
            ></div>

            <!-- Left Sidebar -->
            <aside
                class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 flex flex-col justify-between border-r border-slate-800 transform md:translate-x-0 transition-transform duration-300 ease-in-out"
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            >
                <div class="flex flex-col h-full">
                    <!-- Brand / Logo Area -->
                    <div class="h-16 flex items-center justify-between px-6 border-b border-slate-800">
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                            <!-- Logo Emblem -->
                            <div class="h-9 w-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-500 flex items-center justify-center font-serif text-lg font-bold text-white shadow-lg shadow-indigo-500/20 group-hover:scale-105 transition-transform">
                                D
                            </div>
                            <span class="font-bold text-xl tracking-wider text-white">DURALUX</span>
                        </a>
                        <!-- Close mobile menu button -->
                        <button @click="sidebarOpen = false" class="p-1 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 focus:outline-none md:hidden">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Sidebar Menus -->
                    <nav class="flex-1 py-6 px-4 space-y-1.5 overflow-y-auto">
                        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">General</p>

                        <!-- Dashboard Navigation Link -->
                        <a
                            href="{{ route('dashboard') }}"
                            class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'hover:bg-slate-800/60 hover:text-white' }}"
                        >
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        @if(auth()->user()->can('view users') || auth()->user()->can('manage users') || auth()->user()->can('view roles') || auth()->user()->can('manage roles') || auth()->user()->can('view permissions') || auth()->user()->can('manage permissions') || auth()->user()->can('view logs'))
                            <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-3">Administration</p>
                        @endif

                        <!-- Users Navigation Link -->
                        @if(auth()->user()->can('view users') || auth()->user()->can('manage users'))
                            <a
                                href="{{ route('users.index') }}"
                                class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('users.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'hover:bg-slate-800/60 hover:text-white' }}"
                            >
                                <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('users.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span>Users</span>
                            </a>
                        @endif

                        <!-- Roles Navigation Link -->
                        @if(auth()->user()->can('view roles') || auth()->user()->can('manage roles'))
                            <a
                                href="{{ route('roles.index') }}"
                                class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('roles.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'hover:bg-slate-800/60 hover:text-white' }}"
                            >
                                <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('roles.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span>Roles</span>
                            </a>
                        @endif

                        <!-- Permissions Navigation Link -->
                        @if(auth()->user()->can('view permissions') || auth()->user()->can('manage permissions'))
                            <a
                                href="{{ route('permissions.index') }}"
                                class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('permissions.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'hover:bg-slate-800/60 hover:text-white' }}"
                            >
                                <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('permissions.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                                <span>Permissions</span>
                            </a>
                        @endif

                        <!-- Activity Logs Navigation Link -->
                        @can('view logs')
                            <a
                                href="{{ route('activity-logs.index') }}"
                                class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('activity-logs.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'hover:bg-slate-800/60 hover:text-white' }}"
                            >
                                <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('activity-logs.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Activity Logs</span>
                            </a>
                        @endcan

                        <!-- Form Builder Navigation Link -->
                        @can('manage forms')
                            @php
                                $unreadSubmissionsCount = \App\Models\FormSubmission::where('is_read', false)->count();
                            @endphp
                            <a
                                href="{{ route('forms.index') }}"
                                class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('forms.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'hover:bg-slate-800/60 hover:text-white' }}"
                            >
                                <div class="flex items-center">
                                    <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('forms.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span>Form Builder</span>
                                </div>
                                @if($unreadSubmissionsCount > 0)
                                    <span class="inline-flex items-center justify-center px-2 py-0.5 ml-2 text-[10px] font-bold leading-none text-rose-100 bg-rose-600 rounded-full animate-pulse">
                                        {{ $unreadSubmissionsCount }}
                                    </span>
                                @endif
                            </a>
                        @endcan

                        <!-- Settings Navigation Link -->
                        @can('manage settings')
                            <a
                                href="{{ route('settings.index') }}"
                                class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('settings.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'hover:bg-slate-800/60 hover:text-white' }}"
                            >
                                <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('settings.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Settings</span>
                            </a>
                        @endcan

                        <!-- Tenants Navigation Link (Global Super Admin only) -->
                        @if(Auth::user()->tenant_id === null)
                            <a
                                href="{{ route('tenants.index') }}"
                                class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('tenants.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'hover:bg-slate-800/60 hover:text-white' }}"
                            >
                                <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('tenants.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span>Tenancy Control</span>
                            </a>
                        @endif
                    </nav>

                    <!-- User Footer in Sidebar -->
                    <div class="p-4 border-t border-slate-800 bg-slate-950/40 flex items-center justify-between">
                        <div class="flex items-center space-x-3 overflow-hidden">
                            <div class="h-9 w-9 rounded-full bg-slate-800 flex items-center justify-center font-bold text-white shrink-0">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="truncate">
                                <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Panel -->
            <div class="flex-1 md:pl-64 flex flex-col min-h-screen">
                <!-- Top Header Bar -->
                <header class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-6 sticky top-0 z-30 shadow-sm">
                    <div class="flex items-center space-x-4">
                        <!-- Sidebar toggle button for mobile -->
                        <button @click="sidebarOpen = true" class="p-1.5 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:bg-slate-800 focus:outline-none md:hidden">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <!-- Top Title or Brand Title -->
                        @isset($header)
                            <div class="hidden md:block">
                                {{ $header }}
                            </div>
                        @else
                            <h2 class="font-bold text-lg text-slate-800 dark:text-slate-200">
                                {{ config('app.name', 'Laravel') }}
                            </h2>
                        @endisset
                    </div>

                    <!-- Right Controls / User Menu dropdown -->
                    <div class="flex items-center space-x-3">
                        <!-- Premium Notification Dropdown Component -->
                        <div x-data="{ open: false }" class="relative">
                            <!-- Bell Button -->
                            <button @click="open = !open" class="relative p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none transition-colors">
                                <span class="sr-only">View notifications</span>
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                    <span class="absolute top-1.5 right-1.5 block h-2.5 w-2.5 rounded-full bg-rose-500 ring-2 ring-white dark:ring-slate-900 animate-pulse"></span>
                                @endif
                            </button>

                            <!-- Dropdown Box -->
                            <div
                                x-show="open"
                                @click.away="open = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 mt-2.5 w-80 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden z-50"
                                style="display: none;"
                            >
                                <div class="px-4 py-3.5 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
                                    <span class="text-xs font-semibold text-slate-800 dark:text-slate-200 tracking-wide">Notifications</span>
                                    @if(Auth::user()->unreadNotifications->count() > 0)
                                        <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-[11px] font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                Mark all read
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <div class="max-h-72 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800">
                                    @forelse(Auth::user()->unreadNotifications->take(5) as $notification)
                                        <div class="p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors flex items-start justify-between space-x-2">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center space-x-1.5 mb-1">
                                                    @if(($notification->data['type'] ?? 'info') === 'success')
                                                        <span class="h-2 w-2 rounded-full bg-emerald-500 shrink-0"></span>
                                                    @elseif(($notification->data['type'] ?? 'info') === 'warning')
                                                        <span class="h-2 w-2 rounded-full bg-amber-500 shrink-0"></span>
                                                    @elseif(($notification->data['type'] ?? 'info') === 'error')
                                                        <span class="h-2 w-2 rounded-full bg-rose-500 shrink-0"></span>
                                                    @else
                                                        <span class="h-2 w-2 rounded-full bg-indigo-500 shrink-0"></span>
                                                    @endif
                                                    <p class="text-xs font-semibold text-slate-900 dark:text-white truncate">
                                                        {{ $notification->data['title'] ?? 'System Update' }}
                                                    </p>
                                                </div>
                                                <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">
                                                    {{ $notification->data['message'] ?? '' }}
                                                </p>
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 block">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </span>
                                            </div>

                                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="shrink-0">
                                                @csrf
                                                <button type="submit" class="p-1 rounded-lg text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800" title="Mark as Read">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    @empty
                                        <div class="py-12 px-4 text-center">
                                            <div class="h-10 w-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-3 text-slate-400 dark:text-slate-500">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v2" />
                                                </svg>
                                            </div>
                                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400">All caught up!</p>
                                            <p class="text-[11px] text-slate-400 mt-0.5">No unread notifications.</p>
                                        </div>
                                    @endforelse
                                </div>

                                <div class="p-2 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50">
                                    <a href="{{ route('notifications.index') }}" class="block text-center text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 py-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                                        View Notification Center
                                    </a>
                                </div>
                            </div>
                        </div>

                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center space-x-2 p-1 px-3 rounded-xl border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition duration-150 ease-in-out focus:outline-none">
                                    <div class="h-6 w-6 rounded-full bg-indigo-500 text-white flex items-center justify-center font-bold text-xs">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300 hidden sm:inline">{{ Auth::user()->name }}</span>
                                    <svg class="h-4 w-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </header>

                <!-- Page Content Slot -->
                <main class="flex-1 py-8 px-6 bg-slate-50 dark:bg-slate-950">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Global Real-Time Poller & HTML5 Desktop Web Notifications System -->
        @auth
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let lastPollTime = new Date().toISOString();
                let notificationPermissionGranted = false;

                // Request desktop notification permission from user
                window.requestBrowserNotificationPermission = function() {
                    if (!("Notification" in window)) {
                        console.log("This browser does not support desktop notifications.");
                        return;
                    }
                    Notification.requestPermission().then(permission => {
                        if (permission === "granted") {
                            notificationPermissionGranted = true;
                            showLocalToast("Desktop notifications enabled successfully!", "success");
                        }
                    });
                };

                // Check initial permission
                if ("Notification" in window) {
                    if (Notification.permission === "granted") {
                        notificationPermissionGranted = true;
                    }
                }

                // Show toast alert on the screen dynamically
                function showLocalToast(message, type = 'info', link = null) {
                    const toastId = 'toast_' + Date.now();
                    const toastHtml = `
                        <div id="${toastId}" class="fixed bottom-5 right-5 z-50 max-w-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-2xl shadow-2xl flex items-start space-x-3 transition-all duration-300 transform translate-y-10 opacity-0 cursor-pointer">
                            <div class="h-8 w-8 rounded-full flex items-center justify-center shrink-0 ${
                                type === 'success' ? 'bg-emerald-50 text-emerald-500 dark:bg-emerald-950/40' :
                                type === 'warning' ? 'bg-amber-50 text-amber-500 dark:bg-amber-950/40' :
                                type === 'error' ? 'bg-rose-50 text-rose-500 dark:bg-rose-950/40' :
                                'bg-indigo-50 text-indigo-500 dark:bg-indigo-950/40'
                            }">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-slate-900 dark:text-white">New Update</p>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-2">${message}</p>
                            </div>
                        </div>
                    `;
                    document.body.insertAdjacentHTML('beforeend', toastHtml);
                    const el = document.getElementById(toastId);

                    // Animate In
                    setTimeout(() => {
                        el.classList.remove('translate-y-10', 'opacity-0');
                    }, 50);

                    // Click handler to redirect
                    el.addEventListener('click', () => {
                        if (link) {
                            window.location.href = link;
                        } else {
                            el.classList.add('translate-y-10', 'opacity-0');
                            setTimeout(() => el.remove(), 300);
                        }
                    });

                    // Auto dismiss
                    setTimeout(() => {
                        if (document.getElementById(toastId)) {
                            el.classList.add('translate-y-10', 'opacity-0');
                            setTimeout(() => el.remove(), 300);
                        }
                    }, 6000);
                }

                // Triggers HTML5 native push notifications
                function triggerDesktopPush(title, message, link = null) {
                    if (notificationPermissionGranted) {
                        try {
                            const notification = new Notification(title, {
                                body: message,
                                icon: '/favicon.ico'
                            });
                            if (link) {
                                notification.onclick = () => {
                                    window.focus();
                                    window.location.href = link;
                                };
                            }
                        } catch (err) {
                            console.error("Desktop notification failed to trigger: ", err);
                        }
                    }
                }

                // Perform AJAX polling
                function pollNotifications() {
                    const url = `{{ route('notifications.poll') }}?since=${encodeURIComponent(lastPollTime)}`;
                    fetch(url)
                        .then(res => res.json())
                        .then(data => {
                            if (data.server_time) {
                                lastPollTime = data.server_time;
                            }

                            const unreadList = data.unread || [];
                            unreadList.forEach(notif => {
                                // Trigger both on-screen Toast and native HTML5 desktop push notifications!
                                showLocalToast(notif.title + ": " + notif.message, notif.type, notif.link);
                                triggerDesktopPush(notif.title, notif.message, notif.link);
                            });

                            // If there are new notifications, we reload the dropdown or unread badge dynamically!
                            if (unreadList.length > 0) {
                                // Check if there is a bell badge count to update
                                const badge = document.querySelector('.relative .absolute.bg-rose-500');
                                if (badge) {
                                    badge.classList.remove('hidden');
                                } else {
                                    // Dynamically append red dot badge to bell icon if it wasn't there
                                    const bellBtn = document.querySelector('.relative button[class*="text-slate-500"]');
                                    if (bellBtn && !bellBtn.querySelector('.bg-rose-500')) {
                                        bellBtn.insertAdjacentHTML('beforeend', '<span class="absolute top-1.5 right-1.5 block h-2.5 w-2.5 rounded-full bg-rose-500 ring-2 ring-white dark:ring-slate-900 animate-pulse"></span>');
                                    }
                                }
                            }
                        })
                        .catch(err => console.log("Notification poll error: ", err));
                }

                // Initial request for permission if unasked
                if ("Notification" in window && Notification.permission === "default") {
                    setTimeout(() => {
                        window.requestBrowserNotificationPermission();
                    }, 5000);
                }

                // Start polling interval (every 12 seconds)
                setInterval(pollNotifications, 12000);
            });
        </script>
        @endauth
    </body>
</html>