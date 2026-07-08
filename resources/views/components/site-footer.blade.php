<footer class="site-footer">
    <div class="site-footer__inner">
        <div class="site-footer__grid">
            <div>
                <x-site-logo light />
                <p class="mt-4 text-sm text-white/60 leading-6 max-w-xs">
                    {{ __('Единый онлайн-сервис для проверки автомобилей и документов.') }}
                </p>
            </div>

            <div>
                <p class="site-footer__heading">{{ __('Сервис') }}</p>
                <a href="{{ route('main') }}" class="site-footer__link">{{ __('Все сервисы') }}</a>
                <a href="#tariffs" class="site-footer__link">{{ __('Тарифы') }}</a>
                <a href="#" class="site-footer__link">{{ __('Цены') }}</a>
            </div>

            <div>
                <p class="site-footer__heading">{{ __('Информация') }}</p>
                <a href="#" class="site-footer__link">{{ __('Blog') }}</a>
                <a href="#" class="site-footer__link">{{ __('Company') }}</a>
            </div>

            <div>
                <p class="site-footer__heading">{{ __('Правовая информация') }}</p>
                <a href="#" class="site-footer__link">{{ __('Политика конфиденциальности') }}</a>
            </div>
        </div>

        <p class="site-footer__bottom">
            &copy; {{ date('Y') }} {{ config('app.name', 'БазаБаза') }}. {{ __('Все права защищены.') }}
        </p>
    </div>
</footer>
