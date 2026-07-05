# Урок 02 — Breeze, главная, Filament-каталог

**Цель:** регистрация/вход; **публичная вёрстка** (Blade + Tailwind) главной и страницы VIN; админка Filament для `Service` + `ServiceOffer`.

**Пакет A:** на главной акцент на **одну услугу VIN**; в сидере — 3 услуги (VIN + 2 заглушки). **11 карточек** — пакет B. Вёрстка — [Figma](https://www.figma.com/design/lH5KkodmwnpGfS3pv1SzFD/Vin-%D0%BE%D1%82%D1%87%D0%B5%D1%82); pixel-perfect не требуется.

**Перед стартом:** урок **01** сдан · `php artisan serve` + `npm run dev` · [GLOSSARY.md](../GLOSSARY.md) — Breeze, Filament · [DOMAIN.md](../DOMAIN.md) — каталог, правило «Скоро»

---

## Шаг 1 — Auth (Breeze)

- [ ] Breeze установлен; при регистрации создаётся `Profile`; базовый ЛК работает

**Прочитать:**


| Тема                       | Документация                                                                                                                                                                      |
| -------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Breeze (Blade)             | [Laravel Breeze (дока 11.x, команды те же)](https://laravel.com/docs/11.x/starter-kits#laravel-breeze) · [artisan breeze:install (13.x)](https://artisan.page/13.x/breezeinstall) |
| Событие `Registered`       | [Authentication](https://laravel.com/docs/authentication)                                                                                                                         |
| Listeners                  | [Events](https://laravel.com/docs/events), [Defining Listeners](https://laravel.com/docs/events#defining-listeners)                                                               |
| Middleware `auth`, `guest` | [Middleware](https://laravel.com/docs/middleware)                                                                                                                                 |
| Связанная запись `Profile` | [Eloquent Relationships](https://laravel.com/docs/eloquent-relationships)                                                                                                         |


*Observer vs listener:* [Eloquent Observers](https://laravel.com/docs/eloquent#observers) — для сравнения; для `Registered` нужен **listener**.

**Сделать:**

- `composer require laravel/breeze --dev` → `php artisan breeze:install blade`
- `npm install && npm run build` (или `npm run dev`)
- Listener на `Registered` → создать `Profile` для `user_id`
- Проверить страницы: `/login`, `/register`, `/dashboard`, `/profile`
- `dashboard` — заглушки навигации: «Профиль», «Заказы», «Отчёты»

**Критерий:** регистрация → в БД есть `users` + `profiles`.

---

## Шаг 2 — Layout публичного сайта

- [ ] Общий layout: шапка, футер, меню; наследуют главная и страница услуги

**Прочитать:**


| Тема          | Документация                                                                                               |
| ------------- | ---------------------------------------------------------------------------------------------------------- |
| Blade layouts | [Blade Templates](https://laravel.com/docs/blade), [Components](https://laravel.com/docs/blade#components) |
| Vite          | [Asset Bundling](https://laravel.com/docs/vite)                                                            |


**Сделать:**

- Отдельный layout сайта (не смешивать с Breeze auth layout)
- **Шапка:** логотип, меню (главная, тарифы), вход/регистрация или пользователь + выход
- **Футер:** тарифы, политика (заглушка до урока 06), копирайт
- `@vite(['resources/css/app.css', 'resources/js/app.js'])`

**Критерий:** шапка/футер не дублируются в каждом view.

---

## Шаг 3 — Главная `GET /`

- [ ] Главная из БД, desktop по Figma; «Скоро» для услуг без офферов

**Прочитать:**


| Тема               | Документация                                                                                 |
| ------------------ | -------------------------------------------------------------------------------------------- |
| Controllers, views | [Controllers](https://laravel.com/docs/controllers), [Views](https://laravel.com/docs/views) |
| Routing            | [Routing](https://laravel.com/docs/routing)                                                  |
| Выборка каталога   | [Retrieving Models](https://laravel.com/docs/eloquent#retrieving-models)                     |
| Tailwind           | [Tailwind Docs](https://tailwindcss.com/docs)                                                |
| Пакет A — главная  | [A-mvp-client.md](../docs/packages/A-mvp-client.md)                                          |


**Сделать:**

- Controller: `Service::where('is_active', true)->orderBy('sort_order')`
- Hero / блок VIN (пакет A); сетка карточек из сидера
- Карточка без офферов → «Скоро»
- Данные только из БД

**Критерий:** смена `Service` в Filament отражается на главной.

---

## Шаг 4 — Страница услуги `GET /services/{slug}`

- [ ] Офферы, цены, состав PDF; кнопка-заглушка «Заказать»

**Прочитать:**


| Тема                | Документация                                                                   |
| ------------------- | ------------------------------------------------------------------------------ |
| Route Model Binding | [Route Model Binding](https://laravel.com/docs/routing#route-model-binding)    |
| Eager loading       | [Eager Loading](https://laravel.com/docs/eloquent-relationships#eager-loading) |


**Сделать:**

- Binding по `slug`; `Service` + активные `serviceOffers`
- Список офферов: цена, `document_types` по-русски
- Кнопка «Заказать» / «В корзину» → заглушка (урок 07)
- VIN/ГРЗ/СТС — disabled inputs до урока 07

**Критерий:** смена цены в Filament видна после refresh.

---

## Шаг 5 — Filament

- [ ] CRUD `Service` и `ServiceOffer` в админке

**Прочитать:**


| Тема      | Документация                                                                          |
| --------- | ------------------------------------------------------------------------------------- |
| Установка | [Filament — Installation](https://filamentphp.com/docs/panels/installation)           |
| Resources | [Filament — Resources](https://filamentphp.com/docs/panels/resources/getting-started) |
| Forms     | [Filament — Forms](https://filamentphp.com/docs/forms)                                |


**Сделать:**

```bash
composer require filament/filament
php artisan filament:install --panels
```

- Resource `Service`, `ServiceOffer` (в т.ч. multiselect `document_types`)
- Доступ в панель только admin (`canAccessPanel`)

**Критерий:** цены и офферы меняются без правки кода.

---

## Шаг 6 — Сидер

- [ ] Минимум 3 услуги; у VIN — 2–3 оффера; одна без офферов

**Прочитать:**


| Тема             | Документация                                                      |
| ---------------- | ----------------------------------------------------------------- |
| Seeding          | [Database Seeding](https://laravel.com/docs/seeding)              |
| Factories (опц.) | [Eloquent Factories](https://laravel.com/docs/eloquent-factories) |


**Сделать:**

- VIN (активна) + 2 заглушки; 2–3 оффера у VIN с разными `document_types`
- Одна услуга без офферов → «Скоро» на витрине
- `php artisan db:seed`

---

## Шаг 7 — Адаптив (пакет A)

- [ ] Mobile/tablet: главная, VIN, auth, dashboard/profile

**Прочитать:**


| Тема                | Документация                                                        |
| ------------------- | ------------------------------------------------------------------- |
| Tailwind responsive | [Responsive Design](https://tailwindcss.com/docs/responsive-design) |
| Список страниц MVP  | [A-mvp-client.md § Адаптив](../docs/packages/A-mvp-client.md)       |
| Часы в смете        | [A-mvp-checklist.md](../docs/packages/A-mvp-checklist.md)           |


**Сделать:**

- `sm:`, `md:`, `lg:`; бургер-меню; карточки в колонку на узком экране
- Корзина, checkout, `/privacy` — адаптив в уроках **07**, **06**

**Критерий:** читаемо на 320px+ и планшете.

---

## Шаг 8 — Заглушка `/tariffs`

- [ ] Страница «Скоро» + ссылки в шапке/футере

**Прочитать:**

- [14-subscriptions-tariffs.md](14-subscriptions-tariffs.md) — что будет позже

**Сделать:**

- `GET /tariffs` — простая заглушка
- Ссылки из layout (шаг 2)

---

## Собес

- [ ] §37 (middleware auth), §23 (Bitrix инфоблок) — устно · [100 вопросов](../../../interview/100-questions-middle.md)

## TIME_LOG

- [ ] Записать часы

## Следующий урок

[03 — заказы, Paykeeper, очередь](03-orders-paykeeper-queue-integrations.md)

---

## Справочник

> [DOMAIN.md](../DOMAIN.md) · [A-mvp-checklist.md](../docs/packages/A-mvp-checklist.md)

