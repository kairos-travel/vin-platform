# Урок 13 — API Sanctum (`/api/v1`)

**Цель:** API для мобильного клиента или партнёров: каталог, заказы, отчёты — с Sanctum и JSON Resources.

## Задание (сдать наставнику)

- [ ] **Шаг 1:** prefix `v1`, `auth:sanctum` на защищённых маршрутах.
- [ ] **Шаг 2:** `GET /api/v1/services`, `GET /api/v1/services/{slug}/offers` — публично или с токеном.
- [ ] **Шаг 3:** `POST /api/v1/orders` — создать заказ (одна позиция или из корзины — как в 07).
- [ ] **Шаг 4:** `GET /api/v1/orders`, `GET /api/v1/reports` — только свои (Policy).
- [ ] **Шаг 5:** `ServiceResource`, `ServiceOfferResource`, `OrderResource`, `ReportResource`; пагинация; 422 с `errors`.
- [ ] **Шаг 6:** тесты API: 401 без токена, 403 чужой заказ.
- [ ] **Собес:** §42, §46 — устно.
- [ ] `TIME_LOG`.

## Перед ДЗ

- [Sanctum](https://laravel.com/docs/sanctum), [API Resources](https://laravel.com/docs/eloquent-resources)
- Корневой [02-api-sanctum](../../../homework/02-api-sanctum-resources.md) — эталон структуры
- Уроки **03**, **07**

## Техника

### Маршруты (черновик)

```php
Route::prefix('v1')->group(function () {
    Route::get('services', ...);
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('orders', ...)->only(['index', 'store', 'show']);
        Route::get('reports', ...);
    });
});
```

Оплата через Paykeeper остаётся **web redirect** + webhook — не через JSON (или отдельный `payment_url` в ответе).

**Критерий:** Postman/curl с Bearer — список своих отчётов.

## Следующий урок

[14 — тарифы и подписки](14-subscriptions-tariffs.md)

---

## Справочник

> [10 Laravel theory 1](../../../homework/10-interview-laravel-theory-1.md) §42.
