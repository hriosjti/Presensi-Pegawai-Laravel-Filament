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

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-blue-100 via-white to-green-100">

    <div class="min-h-screen flex flex-col justify-center items-center px-4 sm:px-0">
        <div class="mb-6">
            <a href="/">
                <img class="h-32 sm:h-40 rounded-lg shadow-md transition-transform duration-200 hover:scale-105"
                    src="{{ asset('img/logopuskesmasmulyojati.jpg') }}" alt="Logo Puskesmas">
            </a>
        </div>

        <div
            class="w-full sm:max-w-md bg-white/90 backdrop-blur-lg border border-gray-200 shadow-xl rounded-xl px-8 py-6">
            {{ $slot }}
        </div>

        <footer class="mt-6 text-sm text-gray-600">
            &copy; {{ date('Y') }} Puskesmas Mulyojati. All rights reserved.
        </footer>
    </div>

</body>

</html>
