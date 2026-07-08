@php
    $features = [
        'Мгновенное формирование отчёта',
        'Данные из официальных реестров',
        'Удобный личный кабинет',
        'История всех заказов',
        'Скачивание PDF в один клик',
        'Поддержка VIN, ГРЗ и СТС',
    ];
@endphp

<section class="home-features">
    <div class="site-container">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            <div>
                <span class="home-features__badge">{{ __('Фокусируйтесь на важном') }}</span>

                <h2 class="home-features__title">
                    {{ __('Получите отчёт здесь и сейчас') }}
                </h2>

                <p class="home-features__subtitle">
                    {{ __('Регистрируйтесь на сервисе и получайте отчёты') }}
                </p>

                <ul class="home-features__list">
                    @foreach ($features as $feature)
                        <li class="home-features__item">
                            <span class="home-features__check" aria-hidden="true">
                                <svg class="w-2.5 h-2.5" viewBox="0 0 12 12" fill="none">
                                    <path d="M2 6L5 9L10 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            {{ __($feature) }}
                        </li>
                    @endforeach
                </ul>

                <div class="home-features__actions">
                    @auth
                        <a href="{{ route('dashboard') }}" class="home-features__btn">
                            <span aria-hidden="true">→</span>
                            {{ __('Личный кабинет') }}
                        </a>
                    @else
                        <button type="button" @click="$dispatch('open-auth', 'register')" class="home-features__btn">
                            <span aria-hidden="true">→</span>
                            {{ __('Зарегистрироваться') }}
                        </button>
                    @endauth
                    <p class="home-features__trial">{{ __('Бесплатная версия в течение 7 дней') }}</p>
                </div>
            </div>

            <div class="home-features__phone-wrap" aria-hidden="true">
                <div class="home-features__phone-rings">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <div class="home-features__phone">
                    <div class="home-features__phone-notch"></div>
                    <div class="home-features__phone-screen">
                        <p class="home-features__phone-label">{{ __('Личный кабинет') }}</p>
                        <div class="home-features__phone-card">
                            <div class="home-features__phone-icon">
                                <svg class="w-8 h-8 text-brand-red" viewBox="0 0 32 32" fill="none">
                                    <path d="M8 4H20L24 8V26C24 27.1 23.1 28 22 28H10C8.9 28 8 27.1 8 26V4Z" stroke="currentColor" stroke-width="1.5"/>
                                    <path d="M20 4V8H24" stroke="currentColor" stroke-width="1.5"/>
                                    <path d="M12 16H20M12 20H18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <p class="home-features__phone-card-title">{{ __('Отчёт готов!') }}</p>
                            <p class="home-features__phone-card-text">{{ __('Скачайте отчёт в личном кабинете') }}</p>
                            <span class="home-features__phone-download">{{ __('СКАЧАТЬ ОТЧЁТ') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
