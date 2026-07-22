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
                    <div class="flex items-center space-x-4">
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
    </body>
</html>