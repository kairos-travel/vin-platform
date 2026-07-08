@props(['active' => ''])

<aside class="admin-sidebar">
    <div class="admin-sidebar__brand">
        <x-site-logo light class="!gap-2" />
    </div>

    <nav class="admin-sidebar__nav">
        <p class="admin-sidebar__section">{{ __('Каталог') }}</p>
        <a href="#" @class(['admin-sidebar__link', 'admin-sidebar__link--active' => $active === 'dashboard'])>{{ __('Дашборд') }}</a>
        <a href="#" @class(['admin-sidebar__link', 'admin-sidebar__link--active' => $active === 'services'])>{{ __('Услуги') }}</a>
        <a href="#" @class(['admin-sidebar__link', 'admin-sidebar__link--active' => $active === 'offers'])>{{ __('Офферы') }}</a>

        <p class="admin-sidebar__section">{{ __('Продажи') }}</p>
        <a href="#" @class(['admin-sidebar__link', 'admin-sidebar__link--active' => $active === 'orders'])>{{ __('Заказы') }}</a>
        <a href="#" @class(['admin-sidebar__link', 'admin-sidebar__link--active' => $active === 'reports'])>{{ __('Отчёты') }}</a>

        <p class="admin-sidebar__section">{{ __('Позже') }}</p>
        <a href="#" class="admin-sidebar__link opacity-50 pointer-events-none">{{ __('Тарифы') }}</a>
        <a href="#" class="admin-sidebar__link opacity-50 pointer-events-none">{{ __('Поддержка') }}</a>
        <a href="#" class="admin-sidebar__link opacity-50 pointer-events-none">{{ __('API-логи') }}</a>
    </nav>
</aside>
