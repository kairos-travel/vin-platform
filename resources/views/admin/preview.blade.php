{{--
    Статический preview админки (frontend only).
    Просмотр: добавь в routes/web.php (временно):
    Route::view('/admin-preview', 'admin.preview');
--}}
<x-layouts.admin-preview title="Услуги" active="services">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="admin-page-title">{{ __('Услуги') }}</h1>
        <button type="button" class="admin-btn">{{ __('Создать') }}</button>
    </div>

    <div class="admin-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>{{ __('Название') }}</th>
                    <th>{{ __('Slug') }}</th>
                    <th>{{ __('Офферы') }}</th>
                    <th>{{ __('Статус') }}</th>
                    <th>{{ __('Порядок') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="font-medium">VIN-отчёт</td>
                    <td class="text-brand-menu">vin-otcet</td>
                    <td>2</td>
                    <td><span class="admin-badge admin-badge--green">{{ __('Активна') }}</span></td>
                    <td>10</td>
                </tr>
                <tr>
                    <td class="font-medium">Штрафы ГИБДД</td>
                    <td class="text-brand-menu">strafy-gibdd</td>
                    <td>0</td>
                    <td><span class="admin-badge admin-badge--gray">{{ __('Скоро') }}</span></td>
                    <td>70</td>
                </tr>
                <tr>
                    <td class="font-medium">ЭПТС</td>
                    <td class="text-brand-menu">epts</td>
                    <td>0</td>
                    <td><span class="admin-badge admin-badge--gray">{{ __('Скоро') }}</span></td>
                    <td>80</td>
                </tr>
            </tbody>
        </table>
    </div>

    <p class="mt-6 text-sm text-brand-menu max-w-2xl">
        {{ __('Это макет вёрстки. Реальная админка будет на Filament (урок 02, шаг 5) — таблицы, формы и CRUD генерирует Filament, стили — theme.css + colors в AdminPanelProvider.') }}
    </p>
</x-layouts.admin-preview>
