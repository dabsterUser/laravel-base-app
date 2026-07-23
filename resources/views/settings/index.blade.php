<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <!-- Breadcrumbs -->
                <div class="flex items-center space-x-2 text-xs text-slate-400 dark:text-slate-500 mb-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-indigo-500 transition-colors">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-600 dark:text-slate-400">Settings</span>
                </div>
                <h2 class="font-bold text-2xl text-slate-800 dark:text-slate-100 tracking-tight leading-tight">
                    {{ __('System Settings') }}
                </h2>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 dark:bg-indigo-950/50 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/60 shadow-sm">
                    <svg class="mr-1.5 h-3 w-3 animate-pulse text-indigo-500" fill="currentColor" viewBox="0 0 8 8">
                        <circle cx="4" cy="4" r="3" />
                    </svg>
                    System Active
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6" x-data="{ activeTab: 'smtp', activeProvider: '{{ old('ai_provider', $settings['ai_provider']) }}' }">
        <div class="max-w-4xl mx-auto">

            <!-- Alert / Session Messages -->
            @if(session('message'))
                <div class="mb-6 p-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 dark:border-emerald-800/40 dark:bg-emerald-950/20 dark:text-emerald-300 flex items-start space-x-3 shadow-md shadow-emerald-500/5">
                    <svg class="h-5 w-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="font-semibold text-sm">{{ session('message') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl border border-rose-200 bg-rose-50 text-rose-800 dark:border-rose-800/40 dark:bg-rose-950/20 dark:text-rose-300 flex items-start space-x-3 shadow-md shadow-rose-500/5">
                    <svg class="h-5 w-5 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <p class="font-semibold text-sm">Action Failed</p>
                        <p class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Tabs Selection Navigation -->
            <div class="flex border-b border-slate-200 dark:border-slate-800 mb-6 space-x-4">
                <button
                    @click="activeTab = 'smtp'"
                    :class="activeTab === 'smtp' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300'"
                    class="py-3 px-4 border-b-2 font-medium text-sm transition-all duration-200 focus:outline-none flex items-center space-x-2"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L22 8m-9 11h3m2.5 0a1.5 1.5 0 01-3 0V15a1.5 1.5 0 013 0v4z" />
                    </svg>
                    <span>SMTP Server Connection</span>
                </button>
                <button
                    @click="activeTab = 'ai'"
                    :class="activeTab === 'ai' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300'"
                    class="py-3 px-4 border-b-2 font-medium text-sm transition-all duration-200 focus:outline-none flex items-center space-x-2"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    <span>AI Integrations & Models</span>
                </button>
            </div>

            <!-- TAB 1: SMTP Settings & Test Panel -->
            <div x-show="activeTab === 'smtp'" class="space-y-6" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1">

                <!-- Main SMTP Configuration Form -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                        <h3 class="font-bold text-lg text-slate-800 dark:text-slate-200">SMTP Configurations</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Configure your primary mail server for system notifications, notifications, and reset passwords.</p>
                    </div>

                    <div class="p-6">
                        <form method="POST" action="{{ route('settings.update') }}" id="smtp-save-form" class="space-y-6">
                            @csrf
                            <!-- Hidden input to preserve active AI provider while saving SMTP -->
                            <input type="hidden" name="ai_provider" value="{{ $settings['ai_provider'] }}">
                            <input type="hidden" name="openai_api_key" value="{{ $settings['openai_api_key'] }}">
                            <input type="hidden" name="groq_api_key" value="{{ $settings['groq_api_key'] }}">
                            <input type="hidden" name="anthropic_api_key" value="{{ $settings['anthropic_api_key'] }}">

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- SMTP Host -->
                                <div class="md:col-span-2">
                                    <x-input-label for="smtp_host" :value="__('SMTP Host')" class="font-semibold text-slate-700 dark:text-slate-300" />
                                    <x-text-input id="smtp_host" name="smtp_host" type="text" class="block mt-1.5 w-full rounded-xl border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 shadow-sm" :value="old('smtp_host', $settings['smtp_host'])" required />
                                    <x-input-error :messages="$errors->get('smtp_host')" class="mt-1" />
                                </div>

                                <!-- SMTP Port -->
                                <div>
                                    <x-input-label for="smtp_port" :value="__('SMTP Port')" class="font-semibold text-slate-700 dark:text-slate-300" />
                                    <x-text-input id="smtp_port" name="smtp_port" type="number" class="block mt-1.5 w-full rounded-xl border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 shadow-sm" :value="old('smtp_port', $settings['smtp_port'])" required />
                                    <x-input-error :messages="$errors->get('smtp_port')" class="mt-1" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Username -->
                                <div>
                                    <x-input-label for="smtp_username" :value="__('Username')" class="font-semibold text-slate-700 dark:text-slate-300" />
                                    <x-text-input id="smtp_username" name="smtp_username" type="text" class="block mt-1.5 w-full rounded-xl border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 shadow-sm" :value="old('smtp_username', $settings['smtp_username'])" />
                                    <x-input-error :messages="$errors->get('smtp_username')" class="mt-1" />
                                </div>

                                <!-- Password -->
                                <div x-data="{ show: false }">
                                    <x-input-label for="smtp_password" :value="__('Password')" class="font-semibold text-slate-700 dark:text-slate-300" />
                                    <div class="relative mt-1.5">
                                        <input
                                            id="smtp_password"
                                            name="smtp_password"
                                            :type="show ? 'text' : 'password'"
                                            value="{{ old('smtp_password', $settings['smtp_password']) }}"
                                            class="block w-full rounded-xl border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-slate-900 dark:text-slate-100 placeholder-slate-400"
                                        />
                                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                                            <svg x-show="!show" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <svg x-show="show" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                        </button>
                                    </div>
                                    <x-input-error :messages="$errors->get('smtp_password')" class="mt-1" />
                                </div>

                                <!-- Encryption -->
                                <div>
                                    <x-input-label for="smtp_encryption" :value="__('Encryption')" class="font-semibold text-slate-700 dark:text-slate-300" />
                                    <select id="smtp_encryption" name="smtp_encryption" class="block mt-1.5 w-full rounded-xl border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="tls" {{ old('smtp_encryption', $settings['smtp_encryption']) === 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ old('smtp_encryption', $settings['smtp_encryption']) === 'ssl' ? 'selected' : '' }}>SSL</option>
                                        <option value="none" {{ old('smtp_encryption', $settings['smtp_encryption']) === 'none' ? 'selected' : '' }}>None</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('smtp_encryption')" class="mt-1" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- From Address -->
                                <div>
                                    <x-input-label for="smtp_from_address" :value="__('From Email Address')" class="font-semibold text-slate-700 dark:text-slate-300" />
                                    <x-text-input id="smtp_from_address" name="smtp_from_address" type="email" class="block mt-1.5 w-full rounded-xl border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 shadow-sm" :value="old('smtp_from_address', $settings['smtp_from_address'])" required />
                                    <x-input-error :messages="$errors->get('smtp_from_address')" class="mt-1" />
                                </div>

                                <!-- From Name -->
                                <div>
                                    <x-input-label for="smtp_from_name" :value="__('From Display Name')" class="font-semibold text-slate-700 dark:text-slate-300" />
                                    <x-text-input id="smtp_from_name" name="smtp_from_name" type="text" class="block mt-1.5 w-full rounded-xl border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 shadow-sm" :value="old('smtp_from_name', $settings['smtp_from_name'])" required />
                                    <x-input-error :messages="$errors->get('smtp_from_name')" class="mt-1" />
                                </div>
                            </div>

                            <div class="flex items-center justify-end border-t border-slate-100 dark:border-slate-800 pt-4">
                                <x-primary-button class="rounded-xl px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-500/10 transition">
                                    {{ __('Save SMTP Settings') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Test SMTP Connection Live Panel -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-lg text-slate-800 dark:text-slate-200">Test SMTP Mail Connection</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Verify that your server settings are working properly by sending a dynamic email right now.</p>
                        </div>
                    </div>

                    <div class="p-6">
                        <form method="POST" action="{{ route('settings.test-smtp') }}" class="space-y-4">
                            @csrf
                            <!-- Mirror Host, Port, etc., using simple inputs so testing is independent of saved data -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-slate-50 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-800 p-4 rounded-xl space-y-3">
                                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Dynamic Test Parameters</p>
                                    <p class="text-xs text-slate-400">The test will run on the current values entered in the inputs above. Ensure you have entered valid server details.</p>

                                    <!-- Simple JS triggers to synchronize testing variables from the form above automatically -->
                                    <div class="flex flex-col space-y-1">
                                        <span class="text-xs font-medium text-slate-400">Target SMTP Destination:</span>
                                        <input
                                            id="test_email"
                                            name="test_email"
                                            type="email"
                                            placeholder="recipient@example.com"
                                            value="{{ old('test_email', Auth::user()->email) }}"
                                            class="block w-full mt-1 rounded-xl border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 shadow-sm text-sm"
                                            required
                                        />
                                        <x-input-error :messages="$errors->get('test_email')" class="mt-1" />
                                    </div>
                                </div>

                                <div class="flex flex-col justify-between space-y-4">
                                    <!-- Bind hidden inputs dynamically to form values upon submission -->
                                    <input type="hidden" name="smtp_host" id="test_smtp_host" />
                                    <input type="hidden" name="smtp_port" id="test_smtp_port" />
                                    <input type="hidden" name="smtp_username" id="test_smtp_username" />
                                    <input type="hidden" name="smtp_password" id="test_smtp_password" />
                                    <input type="hidden" name="smtp_encryption" id="test_smtp_encryption" />
                                    <input type="hidden" name="smtp_from_address" id="test_smtp_from_address" />
                                    <input type="hidden" name="smtp_from_name" id="test_smtp_from_name" />

                                    <div class="p-4 rounded-xl bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-100/60 dark:border-indigo-900/40 text-xs text-indigo-700 dark:text-indigo-300">
                                        <p class="font-semibold">Note:</p>
                                        <p class="mt-1">This dynamically overrides the mail driver configurations in memory for testing, meaning you can test connection credentials even before saving them!</p>
                                    </div>

                                    <button
                                        type="submit"
                                        onclick="
                                            document.getElementById('test_smtp_host').value = document.getElementById('smtp_host').value;
                                            document.getElementById('test_smtp_port').value = document.getElementById('smtp_port').value;
                                            document.getElementById('test_smtp_username').value = document.getElementById('smtp_username').value;
                                            document.getElementById('test_smtp_password').value = document.getElementById('smtp_password').value;
                                            document.getElementById('test_smtp_encryption').value = document.getElementById('smtp_encryption').value;
                                            document.getElementById('test_smtp_from_address').value = document.getElementById('smtp_from_address').value;
                                            document.getElementById('test_smtp_from_name').value = document.getElementById('smtp_from_name').value;
                                        "
                                        class="w-full inline-flex items-center justify-center rounded-xl px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-md shadow-indigo-500/15 hover:shadow-indigo-500/25 transition duration-150 ease-in-out"
                                    >
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                        Test & Run SMTP Connection
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            <!-- TAB 2: AI Integrations Panel -->
            <div x-show="activeTab === 'ai'" class="space-y-6" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-cloak>
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                        <h3 class="font-bold text-lg text-slate-800 dark:text-slate-200">AI LLM Provider Connections</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Configure API keys and connect your favorite models (OpenAI GPT, Groq Cloud, Anthropic Claude) seamlessly.</p>
                    </div>

                    <div class="p-6">
                        <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
                            @csrf
                            <!-- Hidden SMTP inputs to preserve while saving AI settings -->
                            <input type="hidden" name="smtp_host" value="{{ $settings['smtp_host'] }}">
                            <input type="hidden" name="smtp_port" value="{{ $settings['smtp_port'] }}">
                            <input type="hidden" name="smtp_username" value="{{ $settings['smtp_username'] }}">
                            <input type="hidden" name="smtp_password" value="{{ $settings['smtp_password'] }}">
                            <input type="hidden" name="smtp_encryption" value="{{ $settings['smtp_encryption'] }}">
                            <input type="hidden" name="smtp_from_address" value="{{ $settings['smtp_from_address'] }}">
                            <input type="hidden" name="smtp_from_name" value="{{ $settings['smtp_from_name'] }}">

                            <!-- Active Provider selection dropdown -->
                            <div class="bg-slate-50 dark:bg-slate-950/40 p-4 border border-slate-200/50 dark:border-slate-800/80 rounded-xl">
                                <x-input-label for="ai_provider" :value="__('Primary Active AI Provider')" class="font-bold text-slate-700 dark:text-slate-300" />
                                <p class="text-xs text-slate-400 dark:text-slate-500 mb-2 mt-0.5">Select the active integration to route all system AI queries.</p>
                                <select id="ai_provider" name="ai_provider" x-model="activeProvider" class="block w-full rounded-xl border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="openai">OpenAI (GPT-4o, GPT-3.5-turbo)</option>
                                    <option value="groq">Groq Cloud (Llama 3, Mixtral)</option>
                                    <option value="anthropic">Anthropic (Claude 3.5 Sonnet, Haiku)</option>
                                </select>
                                <x-input-error :messages="$errors->get('ai_provider')" class="mt-1" />
                            </div>

                            <!-- Keys fields section -->
                            <div class="space-y-6 pt-2">

                                <!-- OpenAI API Key -->
                                <div
                                    x-show="activeProvider === 'openai'"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 -translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    class="border-l-4 border-indigo-500 pl-4 space-y-2"
                                >
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200">OpenAI Configuration</span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-900">Official</span>
                                    </div>
                                    <div x-data="{ show: false }">
                                        <x-input-label for="openai_api_key" :value="__('OpenAI API Key')" class="text-xs text-slate-500 dark:text-slate-400" />
                                        <div class="relative mt-1">
                                            <input
                                                id="openai_api_key"
                                                name="openai_api_key"
                                                :type="show ? 'text' : 'password'"
                                                value="{{ old('openai_api_key', $settings['openai_api_key']) }}"
                                                placeholder="sk-proj-..."
                                                class="block w-full rounded-xl border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-slate-900 dark:text-slate-100 placeholder-slate-400"
                                            />
                                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                                                <svg x-show="!show" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                <svg x-show="show" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                    <x-input-error :messages="$errors->get('openai_api_key')" class="mt-1" />
                                </div>

                                <!-- Groq API Key -->
                                <div
                                    x-show="activeProvider === 'groq'"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 -translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    class="border-l-4 border-emerald-500 pl-4 space-y-2"
                                >
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200">Groq Cloud Configuration</span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-100 dark:border-emerald-900">High-Speed</span>
                                    </div>
                                    <div x-data="{ show: false }">
                                        <x-input-label for="groq_api_key" :value="__('Groq API Key')" class="text-xs text-slate-500 dark:text-slate-400" />
                                        <div class="relative mt-1">
                                            <input
                                                id="groq_api_key"
                                                name="groq_api_key"
                                                :type="show ? 'text' : 'password'"
                                                value="{{ old('groq_api_key', $settings['groq_api_key']) }}"
                                                placeholder="gsk_..."
                                                class="block w-full rounded-xl border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-slate-900 dark:text-slate-100 placeholder-slate-400"
                                            />
                                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                                                <svg x-show="!show" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                <svg x-show="show" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                    <x-input-error :messages="$errors->get('groq_api_key')" class="mt-1" />
                                </div>

                                <!-- Anthropic API Key -->
                                <div
                                    x-show="activeProvider === 'anthropic'"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 -translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    class="border-l-4 border-orange-500 pl-4 space-y-2"
                                >
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200">Anthropic Claude Configuration</span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-orange-50 text-orange-700 dark:bg-orange-950 dark:text-orange-300 border border-orange-100 dark:border-orange-900">Advanced AI</span>
                                    </div>
                                    <div x-data="{ show: false }">
                                        <x-input-label for="anthropic_api_key" :value="__('Anthropic Claude API Key')" class="text-xs text-slate-500 dark:text-slate-400" />
                                        <div class="relative mt-1">
                                            <input
                                                id="anthropic_api_key"
                                                name="anthropic_api_key"
                                                :type="show ? 'text' : 'password'"
                                                value="{{ old('anthropic_api_key', $settings['anthropic_api_key']) }}"
                                                placeholder="sk-ant-..."
                                                class="block w-full rounded-xl border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-slate-900 dark:text-slate-100 placeholder-slate-400"
                                            />
                                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                                                <svg x-show="!show" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                <svg x-show="show" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 01-1.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                    <x-input-error :messages="$errors->get('anthropic_api_key')" class="mt-1" />
                                </div>

                            </div>

                            <div class="flex items-center justify-end border-t border-slate-100 dark:border-slate-800 pt-4">
                                <x-primary-button class="rounded-xl px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-500/10 transition">
                                    {{ __('Save AI Integrations') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
