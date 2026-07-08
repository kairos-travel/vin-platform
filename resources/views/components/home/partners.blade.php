<section class="home-partners" aria-label="{{ __('Партнёры') }}">
    <div class="site-container">
        <div class="home-partners__pill">
            <p class="home-partners__intro">
                {{ __('Мы сотрудничаем только с надёжными источниками:') }}
            </p>
            <ul class="home-partners__logos">
                @foreach (['Автокод', 'Дром', 'Авито', 'Авто.ру', 'Sigmart', 'Arcona'] as $partner)
                    <li class="home-partners__logo">{{ $partner }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
