# Filament — фронтенд и брендинг

Подготовлено **до** `composer require filament/filament`. Бекенд (Resources, Policies) — урок 02, **шаг 4**.

## Что уже есть в репозитории

| Файл | Назначение |
|------|------------|
| `resources/css/filament/admin/theme.css` | Кастомная тема панели (sidebar, primary color) |
| `resources/views/components/admin/sidebar.blade.php` | Sidebar для **статического preview** |
| `resources/views/components/layouts/admin-preview.blade.php` | Layout preview-админки |
| `resources/views/admin/preview.blade.php` | Макет таблицы «Услуги» |
| `resources/css/app.css` | Классы `.admin-*` для preview |

Preview **не** Filament — это Blade + Tailwind, чтобы сверить цвета и структуру до установки пакета.

### Как посмотреть preview

Временно в `routes/web.php`:

```php
Route::view('/admin-preview', 'admin.preview');
```

Открыть: `http://localhost:8000/admin-preview`

---

## Установка Filament (бекенд, урок 02)

```bash
composer require filament/filament:"^3.2"
php artisan filament:install --panels
```

Создаётся `app/Providers/Filament/AdminPanelProvider.php` и URL **`/admin`**.

---

## Подключение темы

**1.** В `AdminPanelProvider::panel()`:

```php
->viteTheme('resources/css/filament/admin/theme.css')
->colors([
    'primary' => Color::hex('#E50D25'),
])
->brandName('БазаБаза')
```

**2.** В `vite.config.js` добавить entry (после установки Filament):

```js
input: [
    'resources/css/app.css',
    'resources/js/app.js',
    'resources/css/filament/admin/theme.css',
],
```

**3.** `npm run build` (или `npm run dev`).

> До установки Filament сборка `theme.css` может падать — файл импортирует `@import .../vendor/filament/...`. Это нормально; подключай entry в Vite **после** `composer require`.

---

## Какие страницы делает Filament (не Blade вручную)

Filament генерирует UI из PHP-классов Resources. Отдельные Blade-views для CRUD **не пишем**.

| Resource | URL (пример) | Урок |
|----------|--------------|------|
| Dashboard | `/admin` | 02, 12 |
| Service | `/admin/services` | 02 |
| ServiceOffer | `/admin/service-offers` | 02 |
| Order (read-only) | `/admin/orders` | 12 |
| Report | `/admin/reports` | 12 |
| IntegrationLog | `/admin/integration-logs` | 12 |
| SupportTicket | `/admin/support-tickets` | 09 |
| TariffPlan | `/admin/tariff-plans` | 14 |

**Технологии:** Filament 3 + Livewire 3 + Alpine (внутри Filament) + Tailwind (тема).

---

## Preview vs Filament

| | Preview (`/admin-preview`) | Filament (`/admin`) |
|--|---------------------------|---------------------|
| Назначение | Вёрстка до установки | Рабочая админка |
| Стек | Blade + Tailwind | Filament + Livewire |
| Данные | Статика в HTML | БД, формы, таблицы |
| Sidebar | `x-admin.sidebar` | Встроенный Filament sidebar |

После установки Filament preview можно оставить для референса или удалить.

---

## Доступ

Только пользователи с `canAccessPanel()` (флаг `is_admin` в пакете A; таблица ролей не обязательна) — настраивается в модели `User`, см. [Filament — Users](https://filamentphp.com/docs/3.x/panels/users#authorizing-access-to-the-panel).
