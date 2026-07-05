@props(['mobile' => false])

@if ($mobile)
    <nav class="flex flex-col gap-3">
        <a href="{{ route('main') }}" @class(['site-header__nav-link', 'site-header__nav-link--active' => request()->routeIs('main')])>
            {{ __('Все сервисы') }}
        </a>
        <span class="site-header__nav-link site-header__nav-link--muted">{{ __('Company') }}</span>
        <a href="#" class="site-header__nav-link">{{ __('Blog') }}</a>
        <a href="#" class="site-header__nav-link">{{ __('Цены') }}</a>
        <a href="#" class="site-header__nav-link inline-flex items-center gap-1">
            {{ __('Информация') }}
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </a>
    </nav>
@else
    <nav {{ $attributes }}>
        <a href="{{ route('main') }}" @class(['site-header__nav-link', 'site-header__nav-link--active' => request()->routeIs('main')])>
            {{ __('Все сервисы') }}
        </a>
        <span class="site-header__nav-link site-header__nav-link--muted">{{ __('Company') }}</span>
        <a href="#" class="site-header__nav-link">{{ __('Blog') }}</a>
        <a href="#" class="site-header__nav-link">{{ __('Цены') }}</a>
        <a href="#" class="site-header__nav-link inline-flex items-center gap-1">
            {{ __('Информация') }}
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </a>
    </nav>
@endif
