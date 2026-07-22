<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Activity Log Details') }}: Log #{{ $log->id }}
            </h2>
            <a href="{{ route('activity-logs.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                &larr; {{ __('Back to Logs') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">{{ __('General Information') }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ __('Log Name') }}</p>
                            <p class="text-base text-gray-900 dark:text-white">{{ $log->log_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ __('Event Type') }}</p>
                            <p class="text-base text-gray-900 dark:text-white capitalize">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                    {{ $log->event ?? 'N/A' }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ __('Description') }}</p>
                            <p class="text-base text-gray-900 dark:text-white">{{ $log->description }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ __('Logged At') }}</p>
                            <p class="text-base text-gray-900 dark:text-white">{{ $log->created_at->format('Y-m-d H:i:s') }} ({{ $log->created_at->diffForHumans() }})</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-200 dark:border-gray-700 pt-6 mb-6">
                        <div>
                            <h4 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">{{ __('Causer (Who did it?)') }}</h4>
                            @if($log->causer)
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $log->causer->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $log->causer->email }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ __('ID') }}: {{ $log->causer_id }} ({{ class_basename($log->causer_type) }})</p>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400 italic">{{ __('System / Anonymous / Guest') }}</p>
                            @endif
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">{{ __('Subject (What was modified?)') }}</h4>
                            @if($log->subject)
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ class_basename($log->subject_type) }} #{{ $log->subject_id }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Class') }}: {{ $log->subject_type }}</p>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400 italic">{{ __('No associated subject') }}</p>
                            @endif
                        </div>
                    </div>

                    @if(!empty($log->properties) && count($log->properties) > 0)
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h3 class="text-lg font-bold pb-3 mb-4">{{ __('Changes / Audit Properties') }}</h3>

                            @if(isset($log->properties['attributes']) || isset($log->properties['old']))
                                <div class="space-y-4">
                                    @if(isset($log->properties['old']))
                                        <div>
                                            <h4 class="text-xs font-bold text-red-500 uppercase tracking-wider mb-2">{{ __('Before / Old Values') }}</h4>
                                            <pre class="p-4 bg-red-50 dark:bg-red-950/20 text-red-800 dark:text-red-300 rounded overflow-x-auto text-xs font-mono border border-red-100 dark:border-red-900/50">{{ json_encode($log->properties['old'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                        </div>
                                    @endif

                                    @if(isset($log->properties['attributes']))
                                        <div>
                                            <h4 class="text-xs font-bold text-green-500 uppercase tracking-wider mb-2">{{ __('After / New Values') }}</h4>
                                            <pre class="p-4 bg-green-50 dark:bg-green-950/20 text-green-800 dark:text-green-300 rounded overflow-x-auto text-xs font-mono border border-green-100 dark:border-green-900/50">{{ json_encode($log->properties['attributes'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <pre class="p-4 bg-gray-50 dark:bg-gray-900/50 text-gray-800 dark:text-gray-300 rounded overflow-x-auto text-xs font-mono border border-gray-200 dark:border-gray-700">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
