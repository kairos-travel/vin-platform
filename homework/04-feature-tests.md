# Урок 04 — Feature-тесты

**Цель:** автоматические тесты критичного коммерческого пути: заказ, идемпотентный webhook, генерация отчёта (fake pipeline).

## Задание (сдать наставнику)

- [ ] **Шаг 1:** тест «авторизованный пользователь создаёт заказ» → `pending_payment`, `OrderItem` с snapshot цены.
- [ ] **Шаг 2:** тест webhook Paykeeper — первый вызов → `paid`, Job dispatched; **второй** с тем же `paykeeper_id` → Job **не** второй раз (`Queue::fake()`).
- [ ] **Шаг 3:** тест `GenerateReportsJob` с отключёнными интеграциями → `Report` `completed`, файл существует.
- [ ] **Шаг 4:** тест Policy — чужой заказ/отчёт 403.
- [ ] **Шаг 5:** тест оффера с `document_types: ["vin","fines"]` → **2** `reports` на один `OrderItem`.
- [ ] **Собес:** §36 (PHPUnit), §19 (транзакции) — устно.
- [ ] `TIME_LOG`.

## Перед ДЗ

- [Testing](https://laravel.com/docs/testing), [HTTP Tests](https://laravel.com/docs/http-tests)
- Урок **03** сдан

## Техника

### Пример: идемпотентность

```php
Queue::fake();

$this->postJson('/webhooks/paykeeper', $payload)->assertOk();
$this->postJson('/webhooks/paykeeper', $payload)->assertOk();

Queue::assertPushed(GenerateReportsJob::class, 1);
```

### Пример: несколько отчётов

```php
$offer = ServiceOffer::factory()->create([
    'document_types' => ['vin', 'fines'],
]);
// ... оплата ...
$this->assertCount(2, $orderItem->fresh()->reports);
```

**Критерий:** `php artisan test` зелёный в CI-стиле (без внешних API).

## После фазы 1

В [уроке 07](07-cart-checkout.md) добавить тест корзины; в [10](10-integrations-live.md) — тесты pipeline с `Http::fake()`.

## Следующий урок

[05 — Docker](05-docker-compose.md)

---

## Справочник

> Корневой [04-feature-tests](../../../homework/04-feature-tests.md) — структура чеклиста. [09 SQL](../../../homework/09-interview-sql.md).
