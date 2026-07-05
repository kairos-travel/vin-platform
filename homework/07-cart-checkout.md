# Урок 07 — Корзина и checkout

**Цель:** несколько офферов в **корзине** → один **заказ** → одна **оплата** → отчёты по каждой позиции.

**Перед стартом:** урок **03** сдан (Paykeeper + Job переиспользуем) · [DOMAIN.md](../DOMAIN.md) — корзина, Offer

---

## Шаг 1 — Cart и CartItem

- [ ] Модели и связи *(миграции уже в уроке 01)*

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Eloquent | [Eloquent Relationships](https://laravel.com/docs/eloquent-relationships) |
| Unique constraint | [Schema Builder](https://laravel.com/docs/migrations#indexes) |

**Сделать:**

- `Cart` 1:1 `Profile` (`profile_id` unique)
- `CartItem`: `service_offer_id`, `input_type`, `input_value`
- `Cart::firstOrCreate(['profile_id' => $profile->id])`

---

## Шаг 2 — «В корзину» и `/cart`

- [ ] Добавление, список, удаление, итог

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Controllers | [Controllers](https://laravel.com/docs/controllers) |
| Validation | [Form Requests](https://laravel.com/docs/validation#form-request-validation) |

**Сделать:**

- Кнопка с страницы оффера; страница `/cart`
- Валидация `input_type` (vin/grz/sts)

---

## Шаг 3 — Checkout в транзакции

- [ ] `Cart` → `Order` + N `OrderItem` + `total_amount`; корзина очищается

**Прочитать:**

| Тема | Документация |
|------|----------------|
| DB transactions | [Database Transactions](https://laravel.com/docs/database#database-transactions) |

**Сделать:**

- `DB::transaction`: создать order/items с `price_snapshot`, удалить `cart_items`
- Убрать прямой `Order` из урока 03 (только Cart → Order)

---

## Шаг 4 — Один Payment на заказ

- [ ] Webhook урока 03; Job на **каждый** `OrderItem`

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Queues | [Queues](https://laravel.com/docs/queues) |

**Сделать:**

- Сумма Paykeeper = `$order->total_amount`
- После `paid` — dispatch Job per item

---

## Шаг 5 — Валидация каталога

- [ ] Нельзя добавить неактивный оффер / услугу без офферов

**Сделать:**

- Проверки перед `CartItem::create`

---

## Шаг 6 — Тест корзины

- [ ] 2 позиции → 1 оплата → 2 набора `Report`

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Testing | [HTTP Tests](https://laravel.com/docs/http-tests) |

**Сделать:**

- Feature-тест полного checkout

**Критерий:** 2 VIN-пакета (разные VIN) → 2×N отчётов.

**UX (п.7):** «Купить сейчас» = add to cart + redirect `/cart`, не обход корзины.

---

## Собес

- [ ] §19 (транзакция checkout), §47 (идемпотентность) — устно

## TIME_LOG

- [ ] Записать часы

## Следующий урок

[08 — гараж](08-garage.md)

---

## Справочник

> [04 — тесты](04-feature-tests.md)
