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

    <!-- 5. Real-time Application Metrics & Analytics Graphs -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Card 1: Form Submissions Performance -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 p-6 shadow-sm">
            <div class="pb-4 border-b border-slate-100 dark:border-slate-800/80 mb-4">
                <h3 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider">Form Submissions Traffic</h3>
                <p class="text-xs text-slate-400">Total entries received across created forms</p>
            </div>
            <div class="h-64 relative flex items-center justify-center">
                <canvas id="formSubmissionsPerformanceCanvas"></canvas>
            </div>
        </div>

        <!-- Card 2: User Roles Distribution -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 p-6 shadow-sm">
            <div class="pb-4 border-b border-slate-100 dark:border-slate-800/80 mb-4">
                <h3 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider">User Roles Distribution</h3>
                <p class="text-xs text-slate-400">Proportion of user profiles assigned per role</p>
            </div>
            <div class="h-64 relative flex items-center justify-center">
                <canvas id="userRolesDistributionCanvas"></canvas>
            </div>
        </div>

        <!-- Card 3: Form Submissions Volume Trend -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 p-6 shadow-sm">
            <div class="pb-4 border-b border-slate-100 dark:border-slate-800/80 mb-4">
                <h3 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider">Submissions Volume Trend</h3>
                <p class="text-xs text-slate-400">Form entries timeline frequency (Last 7 Days)</p>
            </div>
            <div class="h-64 relative flex items-center justify-center">
                <canvas id="submissionsVolumeTrendCanvas"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart JS and interactive initializer -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const isDark = document.documentElement.classList.contains('dark');
            const fontColor = isDark ? '#94a3b8' : '#475569';
            const gridColor = 'rgba(148, 163, 184, 0.1)';

            // Data passed from DashboardController
            const formStats = @json($formStats);
            const roleStats = @json($roleStats);
            const submissionChartData = @json($submissionChartData);

            // 1. Form Submissions Performance (Bar Chart)
            const formLabels = formStats.map(f => f.title);
            const formCounts = formStats.map(f => f.submissions_count);

            const finalFormLabels = formLabels.length ? formLabels : ['Customer Feedback', 'Job Application', 'Lead Form'];
            const finalFormCounts = formLabels.length ? formCounts : [14, 25, 8];

            const ctx1 = document.getElementById('formSubmissionsPerformanceCanvas');
            if (ctx1) {
                new Chart(ctx1, {
                    type: 'bar',
                    data: {
                        labels: finalFormLabels,
                        datasets: [{
                            label: 'Submissions',
                            data: finalFormCounts,
                            backgroundColor: 'rgba(79, 70, 229, 0.75)',  // indigo-600
                            borderColor: 'rgba(79, 70, 229, 1)',
                            borderWidth: 2,
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: gridColor },
                                ticks: { font: { family: 'Figtree', size: 10 }, color: fontColor }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { family: 'Figtree', size: 10 }, color: fontColor }
                            }
                        }
                    }
                });
            }

            // 2. User Roles Distribution (Doughnut/Pie Chart)
            const roleLabels = roleStats.map(r => r.name);
            const roleCounts = roleStats.map(r => r.users_count);

            const finalRoleLabels = roleLabels.length ? roleLabels : ['Super Admin', 'Admin', 'User'];
            const finalRoleCounts = roleLabels.length ? roleCounts : [1, 1, 1];

            const ctx2 = document.getElementById('userRolesDistributionCanvas');
            if (ctx2) {
                new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: finalRoleLabels,
                        datasets: [{
                            data: finalRoleCounts,
                            backgroundColor: [
                                'rgba(147, 51, 234, 0.75)', // purple-600
                                'rgba(236, 72, 153, 0.75)', // pink-500
                                'rgba(16, 185, 129, 0.75)'  // emerald-500
                            ],
                            borderColor: [
                                'rgba(147, 51, 234, 1)',
                                'rgba(236, 72, 153, 1)',
                                'rgba(16, 185, 129, 1)'
                            ],
                            borderWidth: 2,
                            hoverOffset: 12
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: { family: 'Figtree', weight: 'bold', size: 10 },
                                    color: fontColor
                                }
                            }
                        }
                    }
                });
            }

            // 3. Form Submissions Volume Trend (Line Chart)
            const trendLabels = submissionChartData.map(s => s.day);
            const trendCounts = submissionChartData.map(s => s.count);

            const finalTrendLabels = trendLabels.length ? trendLabels : ['Thu', 'Fri', 'Sat', 'Sun', 'Mon', 'Tue', 'Wed'];
            const finalTrendCounts = trendLabels.length ? trendCounts : [3, 8, 4, 9, 12, 15, 11];

            const ctx3 = document.getElementById('submissionsVolumeTrendCanvas');
            if (ctx3) {
                new Chart(ctx3, {
                    type: 'line',
                    data: {
                        labels: finalTrendLabels,
                        datasets: [{
                            label: 'Entries Created',
                            data: finalTrendCounts,
                            backgroundColor: 'rgba(236, 72, 153, 0.1)', // pink-500 light fill
                            borderColor: 'rgba(236, 72, 153, 1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: 'rgba(236, 72, 153, 1)'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: gridColor },
                                ticks: { font: { family: 'Figtree', size: 10 }, color: fontColor }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { family: 'Figtree', size: 10 }, color: fontColor }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>