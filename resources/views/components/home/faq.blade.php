@php
    $items = [
        [
            'q' => 'Как быстро формируется отчёт?',
            'a' => 'Большинство отчётов формируется в течение нескольких минут после оплаты. Сложные запросы могут занять до 15 минут.',
        ],
        [
            'q' => 'Какие данные нужны для проверки?',
            'a' => 'Для VIN-отчёта достаточно VIN, госномера или номера СТС — в зависимости от выбранного типа проверки.',
        ],
        [
            'q' => 'Можно ли скачать отчёт повторно?',
            'a' => 'Да, все купленные отчёты доступны в личном кабинете. PDF можно скачать в любой момент.',
        ],
        [
            'q' => 'Откуда берутся данные?',
            'a' => 'Мы используем официальные и проверенные источники: реестры, базы ГИБДД, страховых и других государственных сервисов.',
        ],
    ];
@endphp

<section class="home-faq">
    <div class="site-container">
        <div class="grid lg:grid-cols-[1fr_1.2fr] gap-10 lg:gap-16 items-start">
            <h2 class="home-faq__title">
                {{ __('Часто задаваемые вопросы') }}
            </h2>

            <div x-data="{ open: null }">
                @foreach ($items as $index => $item)
                    <div class="home-faq__item">
                        <button
                            type="button"
                            class="home-faq__question"
                            @click="open = open === {{ $index }} ? null : {{ $index }}"
                            :aria-expanded="open === {{ $index }}"
                        >
                            {{ __($item['q']) }}
                            <svg
                                class="w-5 h-5 shrink-0 text-brand-menu transition-transform"
                                :class="{ 'rotate-180': open === {{ $index }} }"
                                viewBox="0 0 20 20"
                                fill="none"
                                aria-hidden="true"
                            >
                                <path d="M5 8L10 13L15 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <div
                            x-show="open === {{ $index }}"
                            x-transition
                            x-cloak
                            class="home-faq__answer"
                        >
                            {{ __($item['a']) }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<style>
    [x-cloak] { display: none !important; }
</style>
