# Урок 04 — Feature-тесты

**Цель:** автотесты критичного пути: заказ, идемпотентный webhook, генерация отчёта (fake pipeline).

**Перед стартом:** урок **03** сдан

---

## Шаг 1 — Тест создания заказа

- [ ] Авторизованный пользователь → `pending_payment`, `price_snapshot`

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Testing | [Testing](https://laravel.com/docs/testing) |
| HTTP Tests | [HTTP Tests](https://laravel.com/docs/http-tests) |
| Authentication in tests | [Acting As User](https://laravel.com/docs/http-tests#acting-as-an-authenticated-user) |

**Сделать:**

- Feature-тест: POST формы заказа → `Order` + `OrderItem` с корректным snapshot

---

## Шаг 2 — Тест идемпотентности webhook

- [ ] Два POST с одним `paykeeper_id` → Job dispatched **один** раз

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Queue fakes | [Faking Queue](https://laravel.com/docs/queues#testing) |

**Сделать:**

- `Queue::fake()`; два `postJson('/webhooks/paykeeper', $payload)` → `assertPushed(..., 1)`

---

## Шаг 3 — Тест GenerateReportsJob

- [ ] Stub pipeline → `Report` `completed`, файл существует

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Storage fake | [Storage Fake](https://laravel.com/docs/filesystem#testing) |

**Сделать:**

- Job с отключёнными интеграциями → файл на fake disk

---

## Шаг 4 — Тест Policy

- [ ] Чужой заказ/отчёт → 403

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Authorization tests | [Authorization](https://laravel.com/docs/authorization#authorizing-actions-using-policies) |

**Сделать:**

- Два пользователя; запрос чужого ресурса → 403

---

## Шаг 5 — Тест нескольких Report

- [ ] `document_types: ["vin","fines"]` → 2 `reports` на один `OrderItem`

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Factories | [Eloquent Factories](https://laravel.com/docs/eloquent-factories) |

**Сделать:**

- `ServiceOffer::factory()` с двумя типами → после оплаты `assertCount(2, $reports)`

**Критерий:** `php artisan test` зелёный без внешних API.

---

## Собес

- [ ] §36 (PHPUnit), §19 (транзакции) — устно

## TIME_LOG

- [ ] Записать часы

## Следующий урок

[05 — Docker](05-docker-compose.md)

---

## Справочник

> Корневой [04-feature-tests](../../../homework/04-feature-tests.md) · Тест корзины — в [07](07-cart-checkout.md)
