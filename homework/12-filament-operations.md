# Урок 12 — Filament: операции и поддержка бизнеса

**Цель:** админка для оператора: заказы, платежи, отчёты, логи интеграций, ручной **retry** Job.

## Задание (сдать наставнику)

- [ ] **Шаг 1:** Filament Resource `Order` — read-only список, фильтр по статусу, relation items/payments.
- [ ] **Шаг 2:** Resource `Report` — статус, ссылка на файл, `meta.partial`.
- [ ] **Шаг 3:** Resource `IntegrationLog` — поиск по `order_item_id`, provider.
- [ ] **Шаг 4:** Action «Перезапустить генерацию» на failed Report → `GenerateReportsJob::dispatch`.
- [ ] **Шаг 5:** Action «Отменить заказ» — только `pending_payment` / согласованная политика.
- [ ] **Шаг 6:** дашборд: счётчики open tickets, failed reports, orders today.
- [ ] **Собес:** §42 (админка vs API) — устно.
- [ ] `TIME_LOG`.

## Перед ДЗ

- Уроки **02**, **09**, **10**

## Техника

### Retry

```php
Tables\Actions\Action::make('retry')
    ->visible(fn (Report $r) => $r->status === 'failed')
    ->action(function (Report $report) {
        $report->update(['status' => 'processing']);
        GenerateReportsJob::dispatch($report->orderItem);
    });
```

### Права

Только роль **`admin`** (п. M4). Роль `support` **не делаем** в MVP — тикеты смотришь ты же в Filament. Клиентский `User` не имеет доступа в панель (`canAccessPanel`).

**Критерий:** оператор видит failed отчёт, жмёт retry, воркер поднимает статус.

## Следующий урок

[13 — API Sanctum](13-api-sanctum.md)

---

## Справочник

> Старый [02-api-sanctum](../../../homework/02-api-sanctum-resources.md) (events) — структура API.
