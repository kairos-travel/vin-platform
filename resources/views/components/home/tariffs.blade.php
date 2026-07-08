@php
    $tariffs = [
        [
            'name' => '7 ДНЕЙ',
            'subtitle' => 'AI chatbot, personalized recommendations',
            'price' => '499',
            'featured' => false,
            'features_count' => 4,
        ],
        [
            'name' => '30+ ДНЕЙ',
            'subtitle' => 'AI chatbot, personalized recommendations',
            'price' => '2499',
            'featured' => true,
            'features_count' => 6,
        ],
        [
            'name' => '14 ДНЕЙ',
            'subtitle' => 'AI chatbot, personalized recommendations',
            'price' => '1299',
            'featured' => false,
            'features_count' => 3,
        ],
    ];
@endphp

<section class="home-tariffs" id="tariffs">
    <div class="home-tariffs__decor" aria-hidden="true">
        <div class="home-tariffs__red-band"></div>
        <div class="home-tariffs__dots"></div>
    </div>

    <div class="site-container home-tariffs__inner">
        <h2 class="home-tariffs__title">{{ __('Тарифы') }}</h2>

        <div class="home-tariffs__grid">
            @foreach ($tariffs as $tariff)
                <article @class(['home-tariff-card', 'home-tariff-card--featured' => $tariff['featured']])>
                    <p class="home-tariff-card__name">{{ __($tariff['name']) }}</p>
                    <p class="home-tariff-card__subtitle">{{ __($tariff['subtitle']) }}</p>
                    <p class="home-tariff-card__price">
                        <span class="home-tariff-card__currency">₽</span>{{ $tariff['price'] }}
                    </p>

                    @auth
                        <a href="{{ route('dashboard') }}" class="home-tariff-card__btn">{{ __('Оплатить') }}</a>
                    @else
                        <button type="button" @click="$dispatch('open-auth', 'register')" class="home-tariff-card__btn">
                            {{ __('Оплатить') }}
                        </button>
                    @endauth

                    <ul class="home-tariff-card__features">
                        @for ($i = 0; $i < $tariff['features_count']; $i++)
                            <li class="home-tariff-card__feature">
                                <svg class="home-tariff-card__check" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                                    <path d="M3 8L6 11L13 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                {{ __('Преимущества') }}
                            </li>
                        @endfor
                    </ul>
                </article>
            @endforeach
        </div>

        <div class="home-tariffs__cta">
            @auth
                <a href="{{ route('dashboard') }}" class="home-cta__pill">
                    {{ __('Ещё какой-то призыв к действию') }}
                </a>
            @else
                <button type="button" @click="$dispatch('open-auth', 'register')" class="home-cta__pill w-full">
                    {{ __('Ещё какой-то призыв к действию') }}
                </button>
            @endauth
        </div>
    </div>
</section>
