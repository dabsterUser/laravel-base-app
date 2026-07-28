<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 dark:bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $form->title }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased h-full text-slate-900 dark:text-slate-100 flex items-center justify-center p-4">
    <div class="w-full max-w-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 md:p-8 shadow-xl">
        <!-- Brand / Header -->
        <div class="text-center pb-6 border-b border-slate-100 dark:border-slate-800/80">
            <h1 class="text-2xl font-bold text-slate-950 dark:text-white">{{ $form->title }}</h1>
            @if($form->description)
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">{{ $form->description }}</p>
            @endif
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="py-12 text-center space-y-4">
                <div class="h-16 w-16 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-500 flex items-center justify-center mx-auto shadow-lg shadow-emerald-500/10">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-950 dark:text-white">Submission Successful!</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto leading-relaxed">{{ session('success') }}</p>
                <div class="pt-4">
                    <button onclick="window.location.reload();" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs tracking-wider uppercase rounded-xl shadow transition-colors">
                        Submit another response
                    </button>
                </div>
            </div>
        @else
            <!-- Display Validation Errors -->
            @if($errors->any())
                <div class="p-4 my-4 rounded-xl bg-rose-50 border border-rose-200 dark:bg-rose-950/20 dark:border-rose-800/80 text-rose-800 dark:text-rose-300 space-y-1">
                    <p class="text-xs font-bold">Please correct the following errors:</p>
                    <ul class="list-disc pl-5 text-[11px] space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Body -->
            <form action="{{ route('public.form.submit', $form->id) }}" method="POST" class="pt-6 space-y-5">
                @csrf

                @foreach($form->fields ?? [] as $field)
                    @php
                        $name = $field['name'] ?? '';
                        $type = $field['type'] ?? 'text';
                        $label = $field['label'] ?? 'Field Label';
                        $placeholder = $field['placeholder'] ?? '';
                        $required = !empty($field['required']) && $field['required'] === true;
                        $options = $field['options'] ?? [];
                    @endphp

                    @if($name)
                        <div class="space-y-2">
                            <label for="{{ $name }}" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                {{ $label }}
                                @if($required)
                                    <span class="text-rose-500 font-bold">*</span>
                                @endif
                            </label>

                            <!-- TEXT, EMAIL, NUMBER -->
                            @if(in_array($type, ['text', 'email', 'number']))
                                <input
                                    type="{{ $type === 'number' ? 'number' : ($type === 'email' ? 'email' : 'text') }}"
                                    name="{{ $name }}"
                                    id="{{ $name }}"
                                    value="{{ old($name) }}"
                                    {{ $required ? 'required' : '' }}
                                    placeholder="{{ $placeholder }}"
                                    class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 py-2.5 px-3.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                                >

                            <!-- TEXTAREA -->
                            @elseif($type === 'textarea')
                                <textarea
                                    name="{{ $name }}"
                                    id="{{ $name }}"
                                    rows="4"
                                    {{ $required ? 'required' : '' }}
                                    placeholder="{{ $placeholder }}"
                                    class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 py-2.5 px-3.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                                >{{ old($name) }}</textarea>

                            <!-- DROPDOWN -->
                            @elseif($type === 'select')
                                <select
                                    name="{{ $name }}"
                                    id="{{ $name }}"
                                    {{ $required ? 'required' : '' }}
                                    class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 py-2.5 px-3.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                                >
                                    <option value="">-- Choose Option --</option>
                                    @foreach($options as $option)
                                        <option value="{{ $option }}" {{ old($name) === $option ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
                                </select>

                            <!-- CHECKBOX MULTIPLE -->
                            @elseif($type === 'checkbox')
                                <div class="space-y-1.5 pt-1">
                                    @foreach($options as $option)
                                        <label class="flex items-center space-x-2.5 cursor-pointer group">
                                            <input
                                                type="checkbox"
                                                name="{{ $name }}[]"
                                                value="{{ $option }}"
                                                {{ is_array(old($name)) && in_array($option, old($name)) ? 'checked' : '' }}
                                                class="rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-slate-800 bg-slate-50 dark:bg-slate-950"
                                            >
                                            <span class="text-xs text-slate-600 dark:text-slate-400 group-hover:text-slate-950 dark:group-hover:text-white transition-colors">{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>

                            <!-- RADIO BUTTONS -->
                            @elseif($type === 'radio')
                                <div class="space-y-1.5 pt-1">
                                    @foreach($options as $option)
                                        <label class="flex items-center space-x-2.5 cursor-pointer group">
                                            <input
                                                type="radio"
                                                name="{{ $name }}"
                                                value="{{ $option }}"
                                                {{ old($name) === $option ? 'checked' : '' }}
                                                class="text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-slate-800 bg-slate-50 dark:bg-slate-950"
                                            >
                                            <span class="text-xs text-slate-600 dark:text-slate-400 group-hover:text-slate-950 dark:group-hover:text-white transition-colors">{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs tracking-wider uppercase transition-all duration-200 shadow-lg shadow-indigo-600/20 flex items-center justify-center space-x-1.5">
                    <span>Submit Response</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>
        @endif
    </div>
</body>
</html>
