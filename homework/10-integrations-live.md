# Урок 10 — Живые интеграции API

**Цель:** реальные клиенты вместо stub; ветки **VIN / GRZ / STS**; fallback; частичный PDF.

**Перед стартом:** урок **03** · [DOMAIN.md](../DOMAIN.md) — паттерны Integrations · [цепочка API](https://docs.google.com/spreadsheets/d/1XMaa4ime6GRpaHGWuVBZxS6OzGMP6Itk8Oz_puXxkXc/edit?gid=0#gid=0) · ключи в `.env` (не в git)

---

## Шаг 1 — HTTP-клиенты провайдеров

- [ ] `TronkClient`, `ApiCloudClient`, `SpectrumDataClient` — таймауты, логи

**Прочитать:**

| Тема | Документация |
|------|----------------|
| HTTP Client | [HTTP Client](https://laravel.com/docs/http-client) |
| Timeouts | [HTTP Client — Timeout](https://laravel.com/docs/http-client#timeout) |

**Сделать:**

- `app/Integrations/*/...Client.php`; не HTTP в контроллере

---

## Шаг 2 — `integration_logs`

- [ ] `order_item_id`, `provider`, `request_meta`, `response_status`, `duration_ms`

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Logging | [Logging](https://laravel.com/docs/logging) |

**Сделать:**

- Миграция + запись после каждого шага pipeline

---

## Шаг 3 — ReportPipeline: ветки и fallback

- [ ] По `input_type`; штрафы API CLOUD → Tronk

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Config | [Configuration](https://laravel.com/docs/configuration) |
| DOMAIN pipeline | [DOMAIN.md](../DOMAIN.md) |

**Сделать:**

- Синхрон с Google Sheets в `config/integrations.php`
- DaData — **не в MVP** (нет в Sheets)

---

## Шаг 4 — Частичный PDF

- [ ] Секция «данные не получены»; `meta.partial = true`

**Сделать:**

- 4xx → partial по политике; не падать всем заказом

---

## Шаг 5 — Feature flags на staging

- [ ] `TRONK_ENABLED=true` на staging; остальные по готовности

**Прочитать:**

| Тема | Документация |
|------|----------------|
| `.env` | [Environment](https://laravel.com/docs/configuration#environment-configuration) |

---

## Шаг 6 — Вторая услуга (не VIN)

- [ ] Отдельный pipeline key в конфиге

**Сделать:**

- Например `fines` — отдельные steps

**Критерий:** staging + Tronk → PDF с данными или partial.

**Ошибки:** 5xx/timeout → retry Job (max 3), потом `failed`.

---

## Собес

- [ ] §47 (retry vs fallback), §38 (очередь при долгих API) — устно

## TIME_LOG

- [ ] Записать часы

## Следующий урок

[11 — S3](11-storage-s3.md)

---

## Справочник

> [03 — stub](03-orders-paykeeper-queue-integrations.md) · [08 SOLID](../../../homework/08-interview-solid.md)
