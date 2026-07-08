@props(['mobile' => false])

@if ($mobile)
    <nav class="site-header__mobile-nav">
        <a href="{{ route('main') }}" class="site-header__mobile-nav-link">
            {{ __('Все сервисы') }}
        </a>
        <span class="site-header__mobile-nav-link site-header__nav-link--muted">{{ __('Company') }}</span>
        <a href="#" class="site-header__mobile-nav-link">{{ __('Blog') }}</a>
        <a href="#" class="site-header__mobile-nav-link">{{ __('Цены') }}</a>
        <a href="#" class="site-header__mobile-nav-link">{{ __('Информация') }}</a>
    </nav>
@else
    <nav {{ $attributes }}>
        <a href="{{ route('main') }}" @class(['site-header__nav-link', 'site-header__nav-link--active' => request()->routeIs('main')])>
            {{ __('Все сервисы') }}
        </a>
        <span class="site-header__nav-link site-header__nav-link--muted">{{ __('Company') }}</span>
        <a href="#" class="site-header__nav-link">{{ __('Blog') }}</a>
        <a href="#" class="site-header__nav-link">{{ __('Цены') }}</a>
        <a href="#" class="site-header__nav-link inline-flex items-center gap-1 h-[25px]">
            {{ __('Информация') }}
            <svg class="w-4 h-4 shrink-0" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                <path d="M4 6L8 10L12 6" stroke="#697586" stroke-width="1.5" stroke-miterlimit="16"/>
            </svg>
        </a>
    </nav>
@endif
