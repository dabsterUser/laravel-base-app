<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
    <body class="font-sans text-gray-900 antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col justify-center items-center p-6 bg-slate-50">
            <div class="w-full sm:max-w-lg relative bg-white px-8 sm:px-12 py-10 sm:py-12 shadow-md border border-gray-100 rounded-3xl mt-12 mb-6">
                <!-- Floating Emblem/Logo D -->
                <div class="absolute -top-10 left-1/2 transform -translate-x-1/2">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-lg border border-gray-100/50">
                        <div class="w-14 h-14 bg-[#3b59dd] rounded-xl flex items-center justify-center text-white font-bold font-serif text-3xl shadow-inner">
                            D
                        </div>
                    </div>
                </div>

                {{ $slot }}
            </div>
        </div>
    </body>
</html>
