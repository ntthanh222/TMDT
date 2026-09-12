<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>GoodCafe</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#f7f1e8]">
            <div class="mb-4 flex items-center gap-3 rounded-full bg-white/80 px-4 py-2 shadow-sm ring-1 ring-[#e7d7c4] backdrop-blur-sm">
                <a href="/" class="flex items-center gap-3 text-amber-800">
                    <x-application-logo class="h-9 w-9 text-amber-700" />
                    <span class="text-xl font-bold tracking-tight">GoodCafe</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-2 px-6 py-5 bg-white shadow-lg ring-1 ring-[#e9dcc8] overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
