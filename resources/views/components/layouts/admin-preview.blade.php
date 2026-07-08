@props(['title' => __('Админка'), 'active' => ''])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} — {{ config('app.name', 'БазаБаза') }}</title>
    <x-favicon />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:800|poppins:500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900">
    <div class="admin-shell">
        <x-admin.sidebar :active="$active" />

        <div class="admin-main">
            <header class="admin-topbar">
                <div>
                    <p class="text-xs text-brand-menu uppercase tracking-wide">{{ __('Админ-панель') }} · preview</p>
                    <p class="font-medium text-black">{{ $title }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('main') }}" class="admin-btn--secondary admin-btn !py-1.5">{{ __('На сайт') }}</a>
                    <span class="text-sm text-brand-menu">admin@example.com</span>
                </div>
            </header>

            <main class="admin-content">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
