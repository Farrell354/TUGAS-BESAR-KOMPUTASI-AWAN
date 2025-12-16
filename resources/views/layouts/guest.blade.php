<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'TambalFinder') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

            <div class="mb-6 text-center">
                <a href="/" class="flex items-center justify-center gap-2 group">
                    <div class="bg-blue-600 text-white p-2 rounded-lg shadow-md group-hover:bg-blue-700 transition">
                        <i class="fa-solid fa-map-location-dot text-2xl"></i>
                    </div>
                    <span class="text-2xl font-bold text-gray-800 tracking-tight">Tambal<span class="text-blue-600">Finder</span></span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-white shadow-lg rounded-xl border border-gray-100">
                {{ $slot }}
            </div>

            <div class="mt-8 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} TambalFinder. All rights reserved.
            </div>
        </div>
    </body>
</html>
