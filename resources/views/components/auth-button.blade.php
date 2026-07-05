@props(['mobile' => false])

<div {{ $attributes->merge(['class' => $mobile ? 'flex flex-col gap-3' : '']) }}>
    @auth
        <span @class(['text-sm text-brand-menu truncate max-w-[160px]', 'text-center' => $mobile])>
            {{ Auth::user()->name ?? Auth::user()->login ?? Auth::user()->email }}
        </span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" @class(['site-header__auth-pill', 'w-full justify-center' => $mobile])>
                {{ __('Выход') }}
            </button>
        </form>
    @else
        <a href="{{ route('login') }}" @class(['site-header__auth-pill', 'w-full justify-center' => $mobile])>
            {{ __('Регистрация/ Вход') }}
        </a>
    @endauth
</div>
