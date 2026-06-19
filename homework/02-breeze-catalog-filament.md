# Урок 02 — Breeze, главная, Filament-каталог

**Цель:** регистрация/вход; главная с карточками **нескольких** услуг (VIN обязателен); админка Filament для `Service` + `ServiceOffer`.

## Задание (сдать наставнику)

- [ ] **Шаг 1:** Laravel Breeze (Blade + Tailwind), профиль привязан к `Profile` (создавать при регистрации).
- [ ] **Шаг 2:** `GET /` — главная по [Figma](https://www.figma.com/design/lH5KkodmwnpGfS3pv1SzFD/Vin-%D0%BE%D1%82%D1%87%D0%B5%D1%82): сетка услуг из БД; неактивные / без офферов — «Скоро».
- [ ] **Шаг 3:** страница услуги `GET /services/{slug}` — список офферов VIN (пакеты), цена, состав (`document_types`).
- [ ] **Шаг 4:** Filament: CRUD `Service`, `ServiceOffer` (цены и состав пакетов — **из админки**, не хардкод).
- [ ] **Шаг 5:** сидер: минимум 3 услуги (VIN + 2 заглушки), у VIN — 2–3 оффера; одна услуга **без** офферов («Скоро»).
- [ ] **Шаг 6:** в шапке/футере ссылка на `/tariffs` (страница-заглушка до [урока 14](14-subscriptions-tariffs.md)).
- [ ] **Собес:** §37 (middleware auth), §23 (сравнение с Bitrix инфоблоком) — устно.
- [ ] `TIME_LOG`.

## Перед ДЗ

**Термины (прочитать):** [GLOSSARY.md](../GLOSSARY.md) — **Breeze**, **Filament**.

- [Breeze](https://laravel.com/docs/starter-kits#laravel-breeze)
- [Filament](https://filamentphp.com/docs)
- Урок **01** сдан

**Правило каталога:** активная `Service` в витрине — **минимум 1 Offer**; иначе «Скоро» (`is_active=false`).

## Техника

### Шаг 1 — Breeze + Profile

После `php artisan breeze:install blade`:

- Observer или listener: при `Registered` создать `Profile` для `user_id`.
- ЛК: маршруты `dashboard` → редирект в разделы (пока заглушки «Профиль», «Заказы», «Отчёты»).

### Шаг 2 — главная

- `Service::where('is_active', true)->orderBy('sort_order')`
- Карточка: название, краткое описание, ссылка на `/services/{slug}`
- Tailwind — ориентир Figma, pixel-perfect не требуется

### Шаг 3 — офферы VIN

- Показать `document_types` человекочитаемо («VIN-справка», «Штрафы»)
- Кнопка «Заказать» → ведёт на урок 03 (пока route-заглушка)

### Шаг 4 — Filament

```bash
composer require filament/filament
php artisan filament:install --panels
```

- Resource для `Service` и `ServiceOffer`
- В оффере: multiselect/checkboxes для `document_types`

**Критерий:** изменение цены в Filament отражается на публичной странице.

**П.2 зафиксировано:** только зарегистрированные пользователи; корзина привязана к `Profile`.

## Следующий урок

[03 — заказы, Paykeeper, очередь](03-orders-paykeeper-queue-integrations.md)

---

## Справочник

> [DOMAIN.md](../DOMAIN.md) — Service + Offer. Собесы: [10](../../../homework/10-interview-laravel-theory-1.md), [12 Bitrix](../../../homework/12-interview-bitrix.md).
