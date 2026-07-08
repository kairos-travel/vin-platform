<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'БазаБаза') }}</title>
    <x-favicon />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:800|poppins:500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-gray-900">
    <x-header-menu />

    <main>
        {{ $slot }}
    </main>

    <x-site-footer />

    {{-- Figma 269:516+ — auth popups (frontend only, шаг 2) --}}
    <x-auth.modal
        :reset-token="request()->route('token')"
        :reset-email="request()->query('email')"
    />
</body>
</html>
