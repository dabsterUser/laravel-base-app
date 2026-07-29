<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 dark:text-slate-100 tracking-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- 1. Breadcrumb -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <nav class="flex text-slate-500 dark:text-slate-400 text-xs font-semibold" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                        <svg class="w-4 h-4 mr-2 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                        Admin Panel
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-1 md:ml-2 text-slate-700 dark:text-slate-300">Dashboard</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="text-xs text-slate-400 dark:text-slate-500 font-medium">
            System status: <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">Operational</span>
        </div>
    </div>

    <!-- 3. Counting Cards (Stats Grid) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card 1: Users -->
        <div class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 p-6 shadow-sm hover:shadow-md transition-all duration-200 group">
            <div class="flex items-center justify-between">
                <div>
                    <span class="block text-sm font-semibold text-slate-400 dark:text-slate-500 mb-1">Total Users</span>
                    <span class="text-3xl font-bold text-slate-800 dark:text-white tracking-tight">{{ $totalUsers }}</span>
                </div>
                <div class="h-12 w-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:scale-105 transition-transform duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-indigo-600 dark:text-indigo-400 font-semibold">
                <span class="mr-1">Active Accounts</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </div>

        <!-- Card 2: Roles -->
        <div class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 p-6 shadow-sm hover:shadow-md transition-all duration-200 group">
            <div class="flex items-center justify-between">
                <div>
                    <span class="block text-sm font-semibold text-slate-400 dark:text-slate-500 mb-1">Defined Roles</span>
                    <span class="text-3xl font-bold text-slate-800 dark:text-white tracking-tight">{{ $totalRoles }}</span>
                </div>
                <div class="h-12 w-12 rounded-xl bg-purple-50 dark:bg-purple-950/40 flex items-center justify-center text-purple-600 dark:text-purple-400 group-hover:scale-105 transition-transform duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-purple-600 dark:text-purple-400 font-semibold">
                <span class="mr-1">Access Levels</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </div>

        <!-- Card 3: Permissions -->
        <div class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 p-6 shadow-sm hover:shadow-md transition-all duration-200 group">
            <div class="flex items-center justify-between">
                <div>
                    <span class="block text-sm font-semibold text-slate-400 dark:text-slate-500 mb-1">Permissions</span>
                    <span class="text-3xl font-bold text-slate-800 dark:text-white tracking-tight">{{ $totalPermissions }}</span>
                </div>
                <div class="h-12 w-12 rounded-xl bg-pink-50 dark:bg-pink-950/40 flex items-center justify-center text-pink-600 dark:text-pink-400 group-hover:scale-105 transition-transform duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-pink-600 dark:text-pink-400 font-semibold">
                <span class="mr-1">Security Guards</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </div>

        <!-- Card 4: Activities -->
        <div class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 p-6 shadow-sm hover:shadow-md transition-all duration-200 group">
            <div class="flex items-center justify-between">
                <div>
                    <span class="block text-sm font-semibold text-slate-400 dark:text-slate-500 mb-1">Audit Logs</span>
                    <span class="text-3xl font-bold text-slate-800 dark:text-white tracking-tight">{{ $totalActivities }}</span>
                </div>
                <div class="h-12 w-12 rounded-xl bg-amber-50 dark:bg-amber-950/40 flex items-center justify-center text-amber-600 dark:text-amber-400 group-hover:scale-105 transition-transform duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-amber-600 dark:text-amber-400 font-semibold">
                <span class="mr-1">Recorded Events</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Charts & Flow-Charts section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- 4. Flow-Charts Card -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-slate-800 dark:text-white">Activity Flow Metrics</h3>
                    <p class="text-xs text-slate-400 dark:text-slate-500">Overview of recent dynamic actions logged across the panel</p>
                </div>
                <span class="px-2.5 py-1 text-[11px] font-semibold bg-indigo-500/10 text-indigo-500 rounded-lg">Last 7 Days</span>
            </div>

            <!-- Beautiful interactive vector SVG flow chart / bar chart -->
            <div class="relative h-64 w-full flex items-end justify-between px-2 pt-6">
                <!-- Simple Chart Background lines -->
                <div class="absolute inset-x-0 bottom-8 border-b border-dashed border-slate-100 dark:border-slate-800/50"></div>
                <div class="absolute inset-x-0 bottom-24 border-b border-dashed border-slate-100 dark:border-slate-800/50"></div>
                <div class="absolute inset-x-0 bottom-40 border-b border-dashed border-slate-100 dark:border-slate-800/50"></div>

                @php
                    $maxCount = max(array_column($chartData, 'count')) ?: 1;
                @endphp

                @foreach($chartData as $data)
                    @php
                        $percentage = ($data['count'] / $maxCount) * 80; // Scale to max 80% of height
                    @endphp
                    <div class="flex flex-col items-center flex-1 group z-10">
                        <!-- Count Badge on Hover -->
                        <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 bg-slate-800 dark:bg-slate-700 text-white text-[10px] py-1 px-2 rounded-lg absolute -translate-y-8 font-semibold shadow-lg">
                            {{ $data['count'] }} actions
                        </span>

                        <!-- The animated flow bar -->
                        <div class="w-8 sm:w-12 bg-gradient-to-t from-indigo-600 to-indigo-400 hover:to-indigo-300 rounded-t-lg transition-all duration-500 ease-out cursor-pointer shadow-md shadow-indigo-600/10" style="height: {{ max($percentage, 8) }}%"></div>

                        <!-- Axis Label -->
                        <span class="mt-3 text-xs font-semibold text-slate-400 dark:text-slate-500 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors">
                            {{ $data['day'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Audit Flow Events -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 p-6 shadow-sm">
            <h3 class="text-base font-bold text-slate-800 dark:text-white mb-1">Recent Flow Audit</h3>
            <p class="text-xs text-slate-400 dark:text-slate-500 mb-6">Real-time system state shifts</p>

            <div class="relative pl-6 space-y-6 after:absolute after:inset-y-0 after:left-2 after:w-0.5 after:bg-slate-100 dark:after:bg-slate-800/60">
                @forelse($latestActivities as $activity)
                    <div class="relative">
                        <!-- Marker Icon -->
                        <span class="absolute -left-6 top-1.5 h-3.5 w-3.5 rounded-full border-4 border-white dark:border-slate-900 bg-indigo-500 z-10 shadow-sm"></span>
                        <div>
                            <span class="block text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $activity->description }}</span>
                            <span class="block text-[10px] text-slate-400 mt-0.5 font-medium">By {{ $activity->causer?->name ?? 'System' }} • {{ $activity->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-4">No recent activity detected.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 2. Interactive Datatable Panel -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 shadow-sm overflow-hidden mb-8">
        <!-- Datatable Header -->
        <div class="p-6 border-b border-slate-100 dark:border-slate-800/80 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-slate-800 dark:text-white">Active Users Datatable</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500">Dynamic user search, pagination, and role status controls</p>
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ route('dashboard') }}" class="relative max-w-xs w-full">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search name or email..."
                    class="block w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                />
            </form>
        </div>

        <!-- The Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 dark:bg-slate-950/75 border-b border-slate-100 dark:border-slate-800/80">
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">User Details</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Registered</th>
                        <th class="px-6 py-4 text-right text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="h-9 w-9 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center font-bold text-slate-700 dark:text-slate-300">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="block text-sm font-semibold text-slate-800 dark:text-white">{{ $user->name }}</span>
                                        <span class="block text-xs text-slate-400 dark:text-slate-500">{{ $user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($user->roles as $role)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/10">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500">
                                            No Role
                                        </span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                                    {{ $user->created_at->format('M d, Y') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @can('manage users')
                                    <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-slate-50 hover:bg-indigo-50 hover:text-indigo-600 dark:bg-slate-800 dark:hover:bg-indigo-950/40 dark:hover:text-indigo-400 border border-slate-200 dark:border-slate-700 hover:border-indigo-200 transition-colors">
                                        Manage
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400">View Only</span>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-xs text-slate-400 dark:text-slate-500">
                                No matching records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Datatable Pagination Footer -->
        @if($users->hasPages())
            <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-950/50 border-t border-slate-100 dark:border-slate-800/80">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- 5. Interactive Dynamic Chart & Graph Generator -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 p-6 shadow-sm mb-8"
         x-data="dynamicChartBuilder()">
        <!-- Card Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-slate-100 dark:border-slate-800/80 gap-4 mb-6">
            <div>
                <h3 class="text-base font-bold text-slate-800 dark:text-white">Dynamic Graph & Chart Generator</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500">Simply add or edit attributes (Label & Value) and select visual style to generate graphs instantly.</p>
            </div>

            <div>
                <!-- Reset Button -->
                <button @click="resetToDefaults()" class="px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-800 text-xs font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-colors">
                    Reset Defaults
                </button>
            </div>
        </div>

        <!-- Main Builder Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Inputs Column (1/3 width) -->
            <div class="space-y-5">
                <!-- Title -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Chart Display Title</label>
                    <input type="text" x-model="chartTitle" @input="updateChart()" class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 py-2 px-3 text-xs focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                </div>

                <!-- Chart Type -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Visualization Style</label>
                    <select x-model="chartType" @change="rebuildChart()" class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 py-2 px-3 text-xs focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="bar">Bar Graph</option>
                        <option value="pie">Pie Chart</option>
                        <option value="doughnut">Doughnut Chart</option>
                        <option value="line">Line Graph</option>
                        <option value="polarArea">Polar Area</option>
                        <option value="radar">Radar Chart</option>
                    </select>
                </div>

                <!-- Attributes Editor -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Custom Attributes</label>
                        <button @click="addAttribute()" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center space-x-1">
                            <span>+ Add Attribute</span>
                        </button>
                    </div>

                    <div class="space-y-2.5 max-h-64 overflow-y-auto pr-1">
                        <template x-for="(attr, index) in attributes" :key="index">
                            <div class="flex items-center space-x-2 bg-slate-50 dark:bg-slate-950 p-2.5 rounded-xl border border-slate-100 dark:border-slate-800/80">
                                <!-- Label Input -->
                                <input type="text" x-model="attr.label" @input="updateChart()" placeholder="Label" class="w-2/3 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 py-1 px-2 text-xs focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">

                                <!-- Value Input -->
                                <input type="number" x-model.number="attr.value" @input="updateChart()" placeholder="Value" class="w-1/3 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 py-1 px-2 text-xs focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">

                                <!-- Delete Button -->
                                <button @click="removeAttribute(index)" class="text-rose-500 hover:text-rose-700 p-1 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/20 transition-colors" :disabled="attributes.length <= 1">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Visual Chart Canvas Column (2/3 width) -->
            <div class="lg:col-span-2 flex flex-col items-center justify-center bg-slate-50 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-800/80 rounded-2xl p-6 min-h-[320px] relative">
                <div class="w-full h-72 z-10">
                    <canvas id="dynamicDashboardCanvas" class="w-full h-full"></canvas>
                </div>

                <!-- Absolute background glow effect -->
                <div class="absolute inset-0 bg-gradient-to-tr from-indigo-500/5 via-transparent to-purple-500/5 rounded-2xl pointer-events-none"></div>
            </div>
        </div>
    </div>

    <!-- Chart JS and interactive initializer -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function dynamicChartBuilder() {
            return {
                chartTitle: 'Our Custom Visual Metrics',
                chartType: 'bar',
                attributes: [],
                chartInstance: null,

                init() {
                    const savedTitle = localStorage.getItem('duralux_chart_title');
                    const savedType = localStorage.getItem('duralux_chart_type');
                    const savedAttributes = localStorage.getItem('duralux_chart_attributes');

                    if (savedTitle) this.chartTitle = savedTitle;
                    if (savedType) this.chartType = savedType;

                    if (savedAttributes) {
                        try {
                            this.attributes = JSON.parse(savedAttributes);
                        } catch (e) {
                            this.loadDefaults();
                        }
                    } else {
                        this.loadDefaults();
                    }

                    this.$nextTick(() => {
                        this.rebuildChart();
                    });
                },

                loadDefaults() {
                    this.attributes = [
                        { label: 'Form Registrations', value: 45 },
                        { label: 'Newsletter Signups', value: 82 },
                        { label: 'SMTP Email Sent', value: 64 },
                        { label: 'Support Tickets', value: 28 },
                        { label: 'New Dynamic Roles', value: 12 }
                    ];
                },

                resetToDefaults() {
                    this.chartTitle = 'Our Custom Visual Metrics';
                    this.chartType = 'bar';
                    this.loadDefaults();
                    this.saveToStorage();
                    this.rebuildChart();
                },

                addAttribute() {
                    this.attributes.push({ label: 'New Attribute', value: 10 });
                    this.saveToStorage();
                    this.updateChart();
                },

                removeAttribute(index) {
                    if (this.attributes.length > 1) {
                        this.attributes.splice(index, 1);
                        this.saveToStorage();
                        this.updateChart();
                    }
                },

                saveToStorage() {
                    localStorage.setItem('duralux_chart_title', this.chartTitle);
                    localStorage.setItem('duralux_chart_type', this.chartType);
                    localStorage.setItem('duralux_chart_attributes', JSON.stringify(this.attributes));
                },

                getChartColors() {
                    return {
                        bg: [
                            'rgba(79, 70, 229, 0.75)',  // indigo-600
                            'rgba(147, 51, 234, 0.75)', // purple-600
                            'rgba(236, 72, 153, 0.75)', // pink-500
                            'rgba(245, 158, 11, 0.75)', // amber-500
                            'rgba(16, 185, 129, 0.75)', // emerald-500
                            'rgba(59, 130, 246, 0.75)', // blue-500
                            'rgba(239, 68, 68, 0.75)'   // rose-500
                        ],
                        border: [
                            'rgba(79, 70, 229, 1)',
                            'rgba(147, 51, 234, 1)',
                            'rgba(236, 72, 153, 1)',
                            'rgba(245, 158, 11, 1)',
                            'rgba(16, 185, 129, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(239, 68, 68, 1)'
                        ]
                    };
                },

                rebuildChart() {
                    this.saveToStorage();
                    const ctx = document.getElementById('dynamicDashboardCanvas');
                    if (!ctx) return;

                    if (this.chartInstance) {
                        this.chartInstance.destroy();
                    }

                    const colors = this.getChartColors();
                    const labels = this.attributes.map(a => a.label || '');
                    const dataValues = this.attributes.map(a => Number(a.value) || 0);

                    this.chartInstance = new Chart(ctx, {
                        type: this.chartType,
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Attributes Count',
                                data: dataValues,
                                backgroundColor: colors.bg,
                                borderColor: colors.border,
                                borderWidth: 2,
                                borderRadius: this.chartType === 'bar' ? 6 : 0,
                                hoverOffset: 12
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: ['pie', 'doughnut', 'polarArea', 'radar'].includes(this.chartType),
                                    position: 'bottom',
                                    labels: {
                                        font: { family: 'Figtree', weight: 'bold', size: 10 },
                                        color: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#475569'
                                    }
                                },
                                title: {
                                    display: true,
                                    text: this.chartTitle,
                                    font: { family: 'Figtree', weight: 'bold', size: 14 },
                                    color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
                                    padding: { bottom: 15 }
                                }
                            },
                            scales: ['bar', 'line', 'radar'].includes(this.chartType) ? {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: 'rgba(148, 163, 184, 0.1)' },
                                    ticks: { font: { family: 'Figtree', size: 10 }, color: '#94a3b8' }
                                },
                                x: {
                                    grid: { display: false },
                                    ticks: { font: { family: 'Figtree', size: 10 }, color: '#94a3b8' }
                                }
                            } : undefined
                        }
                    });
                },

                updateChart() {
                    this.saveToStorage();
                    if (!this.chartInstance) return;

                    const labels = this.attributes.map(a => a.label || '');
                    const dataValues = this.attributes.map(a => Number(a.value) || 0);

                    this.chartInstance.data.labels = labels;
                    this.chartInstance.data.datasets[0].data = dataValues;
                    this.chartInstance.options.plugins.title.text = this.chartTitle;

                    this.chartInstance.update();
                }
            };
        }
    </script>
</x-app-layout>