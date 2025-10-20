<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            ::-webkit-scrollbar {
                width: 8px;
            }
            ::-webkit-scrollbar-track {
                background: transparent;
            }
            ::-webkit-scrollbar-thumb {
                background: color-mix(in srgb,var(--tblr-scrollbar-color,var(--tblr-body-color)) 20%,transparent);
                border-radius: 4px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: color-mix(in srgb,var(--tblr-scrollbar-color,var(--tblr-body-color)) 30%,transparent);
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased min-h-screen" style="background-color: #f9fafb;">
        <div class="min-h-screen flex flex-col items-center justify-center pl-[80%] lg:pl-[80%]">
            <div class="text-center mb-4">
                <img src="/images/logo.png" alt="Logo" class="mx-auto" style="height: 60px;">
            </div>
            <div class="w-100 max-w-xl mx-auto px-6 py-4 bg-white bg-opacity-95 shadow-lg overflow-hidden rounded-lg backdrop-blur-sm">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
