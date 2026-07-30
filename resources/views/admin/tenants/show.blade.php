<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Tenant Detail: {{ $tenant->name }}
            </h2>
            <a href="{{ route('tenants.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 active:bg-gray-300 transition ease-in-out duration-150">
                Back to Tenants List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Tenant Metadata Info Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Metadata Info</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm text-gray-600 dark:text-gray-300">
                        <div>
                            <span class="block font-semibold text-gray-500 uppercase tracking-wider text-xs">Name</span>
                            <span class="text-lg font-medium text-gray-900 dark:text-white">{{ $tenant->name }}</span>
                        </div>
                        <div>
                            <span class="block font-semibold text-gray-500 uppercase tracking-wider text-xs">Slug</span>
                            <span class="text-lg font-mono text-gray-900 dark:text-white">{{ $tenant->slug }}</span>
                        </div>
                        <div>
                            <span class="block font-semibold text-gray-500 uppercase tracking-wider text-xs">Created At</span>
                            <span class="text-lg font-medium text-gray-900 dark:text-white">{{ $tenant->created_at->format('M d, Y H:i:s') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tenant Users -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Tenant Users ({{ $tenant->users->count() }})</h3>
                    @if($tenant->users->isEmpty())
                        <p class="text-sm text-gray-500 dark:text-gray-400">No users associated with this tenant.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th scope="col" class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th scope="col" class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                    @foreach($tenant->users as $u)
                                        <tr>
                                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $u->name }}</td>
                                            <td class="px-6 py-4 font-mono text-gray-500 dark:text-gray-400">{{ $u->email }}</td>
                                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $u->created_at->format('Y-m-d') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tenant Forms -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Tenant Forms ({{ $tenant->forms->count() }})</h3>
                    @if($tenant->forms->isEmpty())
                        <p class="text-sm text-gray-500 dark:text-gray-400">No forms associated with this tenant.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                        <th scope="col" class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                    @foreach($tenant->forms as $f)
                                        <tr>
                                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $f->title }}</td>
                                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $f->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ ucfirst($f->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $f->created_at->format('Y-m-d') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
