# Урок 13 — API Sanctum (`/api/v1`)

**Цель:** API для мобильного/партнёров: каталог, заказы, отчёты — Sanctum + JSON Resources.

**Перед стартом:** уроки **03**, **07** · корневой [02-api-sanctum](../../../homework/02-api-sanctum-resources.md)

---

## Шаг 1 — Prefix `v1`, `auth:sanctum`

- [ ] Защищённые маршруты с токеном

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Sanctum | [Laravel Sanctum](https://laravel.com/docs/sanctum) |
| API routing | [Routing](https://laravel.com/docs/routing#api-routes) |

---

## Шаг 2 — Публичный каталог

- [ ] `GET /api/v1/services`, `GET /api/v1/services/{slug}/offers`

**Прочитать:**

| Тема | Документация |
|------|----------------|
| API Resources | [Eloquent API Resources](https://laravel.com/docs/eloquent-resources) |

---

## Шаг 3 — `POST /api/v1/orders`

- [ ] Создание заказа (как в 03/07)

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Validation | [Validation](https://laravel.com/docs/validation) |

---

## Шаг 4 — Свои orders и reports

- [ ] `GET` списки — Policy, только свои

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Authorization | [Authorization](https://laravel.com/docs/authorization) |

---

## Шаг 5 — Resources + пагинация + 422

- [ ] `ServiceResource`, `OrderResource`, `ReportResource`…

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Pagination | [API Resource Pagination](https://laravel.com/docs/eloquent-resources#pagination) |
| Error responses | [Validation Error Format](https://laravel.com/docs/validation#validation-error-response-format) |

---

## Шаг 6 — Тесты API

- [ ] 401 без токена; 403 чужой заказ

**Прочитать:**

| Тема | Документация |
|------|----------------|
| HTTP Tests | [HTTP Tests](https://laravel.com/docs/http-tests) |

**Критерий:** Postman/curl с Bearer — свои отчёты. Оплата — web Paykeeper + webhook (или `payment_url` в ответе).

---

## Собес

- [ ] §42, §46 — устно

## TIME_LOG

- [ ] Записать часы

## Следующий урок

[14 — тарифы](14-subscriptions-tariffs.md)

---

## Справочник

> [10 Laravel theory 1](../../../homework/10-interview-laravel-theory-1.md) §42
