<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Form Builder</span>
            <span class="text-slate-300">/</span>
            <h2 class="font-bold text-xl text-slate-800 dark:text-slate-100 leading-tight">
                Integrations & Submissions: {{ $form->title }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Back to Dashboard Trigger -->
        <div class="flex items-center justify-between">
            <a href="{{ route('forms.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 dark:hover:text-white inline-flex items-center space-x-1.5 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                <span>Back to Forms Dashboard</span>
            </a>

            <a href="{{ route('forms.edit', $form->id) }}" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs tracking-wider uppercase transition-all duration-200 shadow-md">
                Edit Form Schema
            </a>
        </div>

        <!-- Row 1: Embed Codes & Integration Panel -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-6">
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Form Integration Channels</h3>
                <p class="text-xs text-slate-500 mt-1">Copy and paste these codes to integrate this form into your website, applications, or share directly.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Direct Link -->
                <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-800/80 space-y-3">
                    <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Direct Public Link</h4>
                    <p class="text-xs text-slate-500">Perfect for sharing on social media, newsletters, or emails.</p>
                    <div x-data="{ copied: false }" class="space-y-2">
                        <input type="text" readonly value="{{ route('public.form.show', $form->id) }}" class="w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 py-1.5 px-2.5 text-xs text-slate-600 focus:outline-none">
                        <button @click="navigator.clipboard.writeText('{{ route('public.form.show', $form->id) }}'); copied = true; setTimeout(() => copied = false, 1500)" class="w-full py-2 bg-indigo-50 dark:bg-indigo-950/30 hover:bg-indigo-100 text-indigo-600 dark:text-indigo-400 font-semibold text-xs rounded-lg transition-colors flex items-center justify-center space-x-1">
                            <span x-show="!copied">Copy Direct Link</span>
                            <span x-show="copied" class="text-emerald-500" style="display: none;">Copied!</span>
                        </button>
                    </div>
                </div>

                <!-- Iframe Embed -->
                <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-800/80 space-y-3">
                    <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">HTML Iframe Code</h4>
                    <p class="text-xs text-slate-500">Paste inside any HTML page to embed the form directly.</p>
                    <div x-data="{ copied: false }" class="space-y-2">
                        <input type="text" readonly value='<iframe src="{{ route('public.form.show', $form->id) }}" width="100%" height="600" style="border:none;"></iframe>' class="w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 py-1.5 px-2.5 text-xs text-slate-600 focus:outline-none">
                        <button @click="navigator.clipboard.writeText(`<iframe src=\'{{ route('public.form.show', $form->id) }}\' width=\'100%\' height=\'600\' style=\'border:none;\'></iframe>`); copied = true; setTimeout(() => copied = false, 1500)" class="w-full py-2 bg-indigo-50 dark:bg-indigo-950/30 hover:bg-indigo-100 text-indigo-600 dark:text-indigo-400 font-semibold text-xs rounded-lg transition-colors flex items-center justify-center space-x-1">
                            <span x-show="!copied">Copy Iframe Embed Code</span>
                            <span x-show="copied" class="text-emerald-500" style="display: none;">Copied!</span>
                        </button>
                    </div>
                </div>

                <!-- Custom Script -->
                <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-800/80 space-y-3">
                    <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Dynamic Widget Script</h4>
                    <p class="text-xs text-slate-500">Enables dynamic injection onto external platforms.</p>
                    <div x-data="{ copied: false }" class="space-y-2">
                        <input type="text" readonly value="<script src='{{ url('/js/form-embed-widget.js') }}' data-form-id='{{ $form->id }}'></script>" class="w-full rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 py-1.5 px-2.5 text-xs text-slate-600 focus:outline-none">
                        <button @click="navigator.clipboard.writeText(`<script src=\'{{ url('/js/form-embed-widget.js') }}\' data-form-id=\'{{ $form->id }}\'></script>`); copied = true; setTimeout(() => copied = false, 1500)" class="w-full py-2 bg-indigo-50 dark:bg-indigo-950/30 hover:bg-indigo-100 text-indigo-600 dark:text-indigo-400 font-semibold text-xs rounded-lg transition-colors flex items-center justify-center space-x-1">
                            <span x-show="!copied">Copy Widget Script Code</span>
                            <span x-show="copied" class="text-emerald-500" style="display: none;">Copied!</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Submissions Log Table -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Form Submissions ({{ $form->submissions->count() }})</h3>
                <p class="text-xs text-slate-500 mt-1">Review all submissions captured by this form along with technical source headers.</p>
            </div>

            @php
                $fields = $form->fields ?? [];
                $headers = collect($fields)->pluck('label', 'name')->toArray();
            @endphp

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase">
                            <th class="px-6 py-4">Submitted At</th>
                            @foreach($headers as $key => $label)
                                <th class="px-6 py-4">{{ $label }}</th>
                            @endforeach
                            <th class="px-6 py-4">Source metadata</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                        @forelse($form->submissions as $sub)
                            @php
                                $isUnread = isset($unreadIds) && in_array($sub->id, $unreadIds);
                            @endphp
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/20 transition-colors {{ $isUnread ? 'bg-rose-50/30 dark:bg-rose-950/10' : '' }}">
                                <td class="px-6 py-4 font-semibold text-slate-900 dark:text-white whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        @if($isUnread)
                                            <span class="h-2.5 w-2.5 rounded-full bg-rose-500 animate-pulse inline-block" title="New Unread Submission"></span>
                                        @endif
                                        <span>{{ $sub->created_at->format('M d, Y H:i:s') }}</span>
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-normal mt-0.5">{{ $sub->created_at->diffForHumans() }}</div>
                                </td>
                                @foreach($headers as $key => $label)
                                    <td class="px-6 py-4 text-slate-700 dark:text-slate-300">
                                        @php
                                            $val = $sub->data[$key] ?? '';
                                        @endphp
                                        @if(is_array($val))
                                            {{ implode(', ', $val) }}
                                        @else
                                            {{ $val }}
                                        @endif
                                    </td>
                                @endforeach
                                <td class="px-6 py-4">
                                    <div class="text-xs text-slate-500">IP: <span class="font-mono text-slate-700 dark:text-slate-300">{{ $sub->ip_address ?? 'N/A' }}</span></div>
                                    <div class="text-[10px] text-slate-400 max-w-xs truncate mt-0.5" title="{{ $sub->user_agent }}">{{ $sub->user_agent ?? 'N/A' }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($headers) + 2 }}" class="px-6 py-16 text-center text-slate-500">
                                    <div class="h-10 w-10 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center mx-auto mb-3 text-slate-400">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v2" />
                                        </svg>
                                    </div>
                                    <p class="font-semibold text-slate-700 dark:text-slate-300">No submissions received yet</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Publish your form and share the public link to capture user entries.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
