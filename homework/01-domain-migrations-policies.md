# Урок 01 — Домен: миграции, модели, политики

**Цель:** ERD своими руками → миграции → модели → **ProfilePolicy** → черновик `anonymize()`.

**Перед стартом:** урок **00** сдан · [DOMAIN.md](../DOMAIN.md) · шаблон [docs/ERD.md](../docs/ERD.md) · [TARIFFS_EXPLAINED.md](../TARIFFS_EXPLAINED.md)

---

## Шаг 0 — Окружение (MySQL локально)

- [ ] `php artisan migrate` и `php artisan test` — зелёные на Mac

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Миграции | [Migrations](https://laravel.com/docs/migrations) |
| MySQL + Laravel | [Database Configuration](https://laravel.com/docs/database) |

**Сделать:**

- Homebrew: `mysql`, PHP 8.3+ с `pdo_mysql`
- БД `vin_platform`, пользователь `vin` (как на VPS, пароль свой)
- `.env`: `DB_CONNECTION=mysql`, не SQLite
- `composer install`, `php artisan key:generate`, `php artisan migrate`, `php artisan serve`

**Критерий:** локальная БД = MySQL, как на staging.

---

## Шаг 1 — ERD (до кода)

- [ ] Диаграмма фазы A сдана наставнику **до миграций**

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Сущности проекта | [DOMAIN.md](../DOMAIN.md) |
| Шаблон ERD | [docs/ERD.md](../docs/ERD.md) |
| Инструмент | [dbdiagram.io](https://dbdiagram.io) |

**Сделать:**

- Нарисовать: `users` → `profiles` → `orders` → `order_items` → `reports`, `payments`, `services` → `service_offers`
- `OrderItem` → `service_offer_id` (не `service_id`)
- Несколько `Report` на один `OrderItem` при нескольких `document_types`
- Тарифы (`tariff_*`) — пометка «урок 14», не в фазе A
- Короткий текст: корзина → заказ → оплата → отчёт

**Критерий:** OK наставника на ERD → только тогда шаг 2.

---

## Шаг 2 — Миграции

- [ ] Все миграции фазы A; `php artisan migrate`

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Миграции, FK, JSON | [Migrations](https://laravel.com/docs/migrations) |
| Soft Deletes | [Soft Deleting](https://laravel.com/docs/eloquent#soft-deleting) |

**Сделать (порядок):**

1. `profiles` — `user_id`, ФИО, `phone`, `softDeletes`
2. `services` — `slug`, `name`, `description`, `is_active`, `sort_order`
3. `service_offers` — `service_id`, `slug`, `price`, `document_types` (json)
4. `orders` — `profile_id`, `status`, `total_amount`
5. `order_items` — `order_id`, `service_offer_id`, `input_type`, `input_value`, `price_snapshot`
6. `payments` — `order_id`, `paykeeper_id` unique nullable, `amount`, `status`, `paid_at`
7. `reports` — `order_item_id`, `type`, `status`, `file_path`, `meta`

*Опционально заранее:* `carts`, `cart_items`, `service_offer_integration_steps` (уроки 07/10).

**Критерий:** `migrate` без ошибок; FK на месте.

---

## Шаг 3 — Модели и связи

- [ ] Модели + `$fillable`, `$casts`, связи; проверка в tinker

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Eloquent | [Eloquent](https://laravel.com/docs/eloquent) |
| Relationships | [Eloquent Relationships](https://laravel.com/docs/eloquent-relationships) |
| Casts | [Attribute Casting](https://laravel.com/docs/eloquent-mutators#attribute-casting) |
| Enums (опц.) | [Enum Casting](https://laravel.com/docs/eloquent-mutators#enum-casting) |

**Сделать:**

- `Profile`, `Service`, `ServiceOffer`, `Order`, `OrderItem`, `Payment`, `Report`, `Cart`, `CartItem`…
- `ServiceOffer`: cast `document_types` → `array`, `price` → `decimal:2`
- `OrderItem` → `hasMany Report`
- Tinker: `$user->profile`, `$offer->service`, `$orderItem->reports`

**Критерий:** связи работают в tinker.

---

## Шаг 4 — ProfilePolicy

- [ ] `view` / `update` / `delete` — только свой профиль

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Authorization | [Authorization](https://laravel.com/docs/authorization) |
| Policies | [Creating Policies](https://laravel.com/docs/authorization#creating-policies) |
| `$user->can()` | [Authorizing Actions](https://laravel.com/docs/authorization#authorizing-actions-using-policies) |

**Сделать:**

- `php artisan make:policy ProfilePolicy --model=Profile`
- `$profile->user_id === $user->id` для view/update/delete
- Tinker: свой профиль → `can('view')` true; чужой → false

**Критерий:** два пользователя, чужой `can('view')` → false.

---

## Шаг 5 — Анонимизация (черновик)

- [ ] Метод `Profile::anonymize()` без UI удаления

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Update models | [Updating Models](https://laravel.com/docs/eloquent#inserting-and-updating-models) |
| Soft Deletes | [Soft Deleting](https://laravel.com/docs/eloquent#soft-deleting) |
| DB transactions | [Database Transactions](https://laravel.com/docs/database#database-transactions) |

**Сделать:**

- Заменить ФИО на плейсхолдер, `phone` → null, `delete()` (soft)
- UI + logout + блокировка `User` — **урок 06**
- Основание 152-ФЗ — текст в уроке 06

**Критерий:** метод вызывается в tinker, ПДн в профиле стёрты.

---

## Собес

- [ ] §15 (нормализация), §9 (SRP) — устно · [100 вопросов](../../../interview/100-questions-middle.md)

## TIME_LOG

- [ ] Записать часы

## Следующий урок

[02 — Breeze, каталог, Filament](02-breeze-catalog-filament.md)

---

## Справочник

> [DOMAIN.md](../DOMAIN.md) — несколько Report на один OrderItem.
