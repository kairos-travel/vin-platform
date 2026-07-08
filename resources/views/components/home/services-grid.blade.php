{{-- TODO (урок 02, шаг 3): заменить на $services из контроллера / сидера --}}
{{-- Пакет A: 3 услуги (VIN + 2 заглушки). Полная сетка 11+ — пакет B. --}}
@php
    $services = [
        ['name' => 'VIN-отчёт', 'available' => true, 'url' => '#'],
        ['name' => 'Штрафы ГИБДД', 'available' => false],
        ['name' => 'ЭПТС', 'available' => false],
    ];
@endphp

<section class="home-services">
    <div class="site-container">
        <div class="home-services__grid">
            @foreach ($services as $service)
                @if ($service['available'])
                    <a href="{{ $service['url'] ?? '#' }}" class="home-service-card group">
                        <span class="home-service-card__title">{{ $service['name'] }}</span>
                        <span class="home-service-card__link group-hover:gap-2">
                            {{ __('Заказать') }}
                            <svg class="w-4 h-4" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                                <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </a>
                @else
                    <div class="home-service-card home-service-card--soon">
                        <span class="home-service-card__title">{{ $service['name'] }}</span>
                        <span class="home-service-card__soon">{{ __('Скоро') }}</span>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
