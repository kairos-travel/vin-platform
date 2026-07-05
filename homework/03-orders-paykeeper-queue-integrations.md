# Урок 03 — «Купить сейчас»: заказ, Paykeeper, очередь, stub API

**Цель:** end-to-end VIN **учебно без корзины** (одна позиция → `Order`): Paykeeper (test) → Job → PDF. В продукте основной путь — корзина ([урок 07](07-cart-checkout.md)).

**Перед стартом:** уроки **01–02** сданы · [GLOSSARY.md](../GLOSSARY.md) — Paykeeper, webhook, идемпотентность, Job, pipeline · [DOMAIN.md](../DOMAIN.md#паттерны-с-примерами)

---

## Шаг 1 — Форма заказа → Order + OrderItem

- [ ] `Order` (`pending_payment`) + `OrderItem` с `price_snapshot`

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Form validation | [Validation](https://laravel.com/docs/validation) |
| Controllers | [Controllers](https://laravel.com/docs/controllers) |

**Сделать:**

- Форма: VIN/GRZ/STS + выбор оффера
- `Order::create` + `items()->create` с `price_snapshot` = цена оффера на момент заказа

**Критерий:** заказ в БД со статусом `pending_payment`.

---

## Шаг 2 — Paykeeper + идемпотентный webhook

- [ ] Редирект на оплату; webhook помечает `paid`; повтор POST не дублирует обработку

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Paykeeper (внешняя) | [paykeeper.ru](https://paykeeper.ru/) — тестовый кабинет |
| CSRF except | [CSRF](https://laravel.com/docs/csrf) |
| Идемпотентность | [DOMAIN.md § Идемпотентность](../DOMAIN.md) |

**Сделать:**

- `POST /webhooks/paykeeper` без CSRF
- Проверка подписи; `paykeeper_id` unique на `payments`
- Повторный webhook → `200 OK`, без второй обработки

**Критерий:** один `paykeeper_id` → один `paid`.

---

## Шаг 3 — Report(s) + GenerateReportsJob

- [ ] После `paid` — N `Report` по `document_types`; Job в очередь

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Queues | [Queues](https://laravel.com/docs/queues) |
| Dispatching jobs | [Dispatching Jobs](https://laravel.com/docs/queues#dispatching-jobs) |

**Сделать:**

- Для каждого типа из `$offer->document_types` → `Report` со статусом `processing`
- `GenerateReportsJob::dispatch($orderItem)` — **не** тяжёлая работа в webhook

**Критерий:** оффер `["vin","fines"]` → 2 записи `reports`.

---

## Шаг 4 — config/integrations.php

- [ ] Цепочка API из [Google Sheets](https://docs.google.com/spreadsheets/d/1XMaa4ime6GRpaHGWuVBZxS6OzGMP6Itk8Oz_puXxkXc/edit?gid=0#gid=0); feature flags

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Configuration | [Configuration](https://laravel.com/docs/configuration) |
| `.env` | [Environment Configuration](https://laravel.com/docs/configuration#environment-configuration) |

**Сделать:**

- `config/integrations.php`: pipelines, providers, `INTEGRATION_*_ENABLED`
- На dev: `enabled=false` → stub-данные в PDF

---

## Шаг 5 — Integrations + PDF (stub)

- [ ] `TronkClient` stub, `ReportPipeline`, PDF на диск

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Service container | [Service Container](https://laravel.com/docs/container) |
| Storage | [Filesystem](https://laravel.com/docs/filesystem) |
| Blade PDF | [Views](https://laravel.com/docs/views) |

**Сделать:**

- `app/Integrations/Tronk/TronkClient.php`, `Pipeline/ReportPipeline.php`, `Jobs/GenerateReportsJob.php`
- Pipeline читает шаги из конфига; stub при выключенных интеграциях
- Частичный отчёт: `meta.partial=true`, секция «данные не получены»

**Критерий:** `queue:work` → `Report` `completed`, файл на диске.

---

## Шаг 6 — ЛК «Заказы» и «Отчёты»

- [ ] Списки в ЛК; скачивание PDF

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Authorization | [Authorization](https://laravel.com/docs/authorization) |
| File download | [Filesystem](https://laravel.com/docs/filesystem#file-downloads) |

**Сделать:**

- Заказы: дата, оффер, сумма, статус
- Отчёты: тип, статус, кнопка download
- Только свои записи (Policy позже в уроке 04)

**Критерий:** тестовый платёж → worker → PDF в ЛК.

---

## Собес

- [ ] §38 (очереди), §47 (идемпотентность) — устно

## TIME_LOG

- [ ] Записать часы

## Дальше

- Корзина → [07](07-cart-checkout.md) · Тарифы → [14](14-subscriptions-tariffs.md) · Live API → [10](10-integrations-live.md)

## Следующий урок

[04 — feature-тесты](04-feature-tests.md)

---

## Справочник

> [DOMAIN.md](../DOMAIN.md) · [08 SOLID](../../../homework/08-interview-solid.md)
