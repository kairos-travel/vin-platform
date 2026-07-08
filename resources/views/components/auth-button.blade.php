@props(['mobile' => false, 'compact' => false])

@php
    $initialUser = Auth::check()
        ? (Auth::user()->name ?? Auth::user()->login ?? Auth::user()->email)
        : null;
@endphp

<div
    x-data='headerAuth({ loggedIn: @json(Auth::check()), userName: @json($initialUser), dashboardUrl: @json(route('dashboard')) })'
    {{ $attributes->merge(['class' => $mobile ? 'w-full' : ($compact ? '' : 'flex items-center gap-4 min-h-[34px]')]) }}
>
    @auth
        @if ($compact)
            <a href="{{ route('dashboard') }}" class="site-header__auth-pill--compact">
                <span class="sm:hidden">{{ __('Кабинет') }}</span>
                <span class="hidden sm:inline">{{ __('Личный кабинет') }}</span>
            </a>
        @elseif ($mobile)
            <div class="site-header__mobile-nav">
                <a
                    href="{{ route('dashboard') }}"
                    class="site-header__auth-user--mobile"
                    title="{{ Auth::user()->email ?? Auth::user()->login }}"
                >
                    {{ $initialUser }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="site-header__auth-logout--mobile">
                        {{ __('Выход') }}
                    </button>
                </form>
            </div>
        @else
            <a
                href="{{ route('dashboard') }}"
                class="site-header__auth-user"
                title="{{ Auth::user()->email ?? Auth::user()->login }}"
            >
                {{ $initialUser }}
            </a>
            <form method="POST" action="{{ route('logout') }}" class="inline-flex">
                @csrf
                <button type="submit" class="site-header__auth-logout">
                    {{ __('Выход') }}
                </button>
            </form>
        @endif
    @else
        @if ($compact)
            <button
                type="button"
                x-bind:class="{ 'hidden': loggedIn }"
                x-on:click="$dispatch('open-auth', 'login')"
                class="site-header__auth-pill--compact"
            >
                <span class="sm:hidden">{{ __('Вход') }}</span>
                <span class="hidden sm:inline">{{ __('Регистрация/ Вход') }}</span>
            </button>
            <a
                x-cloak
                x-bind:class="{ 'hidden': ! loggedIn }"
                :href="dashboardUrl"
                class="site-header__auth-pill--compact"
            >
                <span class="sm:hidden">{{ __('Кабинет') }}</span>
                <span class="hidden sm:inline">{{ __('Личный кабинет') }}</span>
            </a>
        @elseif ($mobile)
            <button
                type="button"
                x-bind:class="{ 'hidden': loggedIn }"
                x-on:click="$dispatch('open-auth', 'login')"
                class="site-header__auth-pill--mobile w-full"
            >
                {{ __('Регистрация/ Вход') }}
            </button>
            <div
                x-cloak
                x-bind:class="{ 'site-header__mobile-nav': loggedIn, 'hidden': ! loggedIn }"
            >
                <a
                    :href="dashboardUrl"
                    class="site-header__auth-user--mobile"
                    :title="userName"
                    x-text="userName"
                ></a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="site-header__auth-logout--mobile">
                        {{ __('Выход') }}
                    </button>
                </form>
            </div>
        @else
            <button
                type="button"
                x-bind:class="{ 'hidden': loggedIn }"
                x-on:click="$dispatch('open-auth', 'login')"
                class="site-header__auth-pill"
            >
                {{ __('Регистрация/ Вход') }}
            </button>
            <div
                x-cloak
                x-bind:class="{ 'flex items-center gap-4': loggedIn, 'hidden': ! loggedIn }"
            >
                <a
                    :href="dashboardUrl"
                    class="site-header__auth-user"
                    :title="userName"
                    x-text="userName"
                ></a>
                <form method="POST" action="{{ route('logout') }}" class="inline-flex">
                    @csrf
                    <button type="submit" class="site-header__auth-logout">
                        {{ __('Выход') }}
                    </button>
                </form>
            </div>
        @endif
    @endauth
</div>
