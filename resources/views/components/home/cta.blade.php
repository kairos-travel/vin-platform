<section class="home-cta">
    <div class="site-container">
        @auth
            <a href="{{ route('dashboard') }}" class="home-cta__pill">
                {{ __('Перейти в личный кабинет') }}
            </a>
        @else
            <button type="button" @click="$dispatch('open-auth', 'register')" class="home-cta__pill w-full">
                {{ __('Начать проверку — регистрация за минуту') }}
            </button>
        @endauth
    </div>
</section>
