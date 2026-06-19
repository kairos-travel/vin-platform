# Урок 10 — Живые интеграции API

**Цель:** заменить stub-провайдеры на реальные клиенты; ветвления **VIN / GRZ / STS** по [цепочке API](https://docs.google.com/spreadsheets/d/1XMaa4ime6GRpaHGWuVBZxS6OzGMP6Itk8Oz_puXxkXc/edit?gid=0#gid=0); **частичный PDF**.

## Задание (сдать наставнику)

- [ ] **Шаг 1:** `TronkClient`, `ApiCloudClient`, `SpectrumDataClient` — HTTP + таймауты + логирование.
- [ ] **Шаг 2:** `integration_logs` — `order_item_id`, `provider`, `request_meta`, `response_status`, `duration_ms`.
- [ ] **Шаг 3:** `ReportPipeline` — ветки по `input_type`; fallback штрафов API CLOUD → Tronk.
- [ ] **Шаг 4:** частичный отчёт: секция недоступна → в PDF «данные не получены», `reports.meta.partial = true`.
- [ ] **Шаг 5:** feature flags — включить Tronk на staging; остальные по готовности.
- [ ] **Шаг 6:** вторая услуга (не VIN) — отдельный pipeline key в `config/integrations.php`.
- [ ] **Собес:** §47 (retry vs fallback), §38 (очередь при долгих API) — устно.
- [ ] `TIME_LOG`.

## Перед ДЗ

- [DOMAIN.md](../DOMAIN.md) — паттерны Integrations
- Урок **03**, тестовые ключи в `.env` (не коммитить)

## Техника

### Конфиг (источник правды)

```php
// config/integrations.php — синхронизировать с Google Sheets
'pipelines' => [
    'vin' => ['steps' => [...]],
    'fines' => ['steps' => [...]],
],
```

**DaData:** не в [цепочке Sheets](https://docs.google.com/spreadsheets/d/1XMaa4ime6GRpaHGWuVBZxS6OzGMP6Itk8Oz_puXxkXc/edit?gid=0#gid=0) — в MVP **не подключаем**. Если позже появится в ТЗ — optional step.

### Ошибки

- 4xx от провайдера → лог + partial/failed по политике услуги
- 5xx / timeout → `retry` Job (max 3), потом failed

**Критерий:** на staging с `TRONK_ENABLED=true` реальный запрос → PDF с данными или partial.

## Следующий урок

[11 — S3](11-storage-s3.md)

---

## Справочник

> [08 SOLID](../../../homework/08-interview-solid.md). [03 — stub](03-orders-paykeeper-queue-integrations.md).
