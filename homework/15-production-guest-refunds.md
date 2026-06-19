# Урок 15 — Прод: возвраты, мониторинг, runbook

**Цель:** коммерческая готовность без гостевого checkout (**п.2 — только регистрация**): возвраты Paykeeper, failed jobs, rate limit, health, runbook.

## Задание (сдать наставнику)

- [ ] **Шаг 1:** возврат **вручную**: в Filament Action «Отметить возврат» + фактический возврат в кабинете Paykeeper; `Payment::refunded`; отмена Job если отчёт `processing`. Авто-возврат — не в MVP.
- [ ] **Шаг 2:** `failed_jobs` — просмотр в Filament или `reports:retry-failed`.
- [ ] **Шаг 3:** rate limit на `/webhooks/paykeeper` и формы заказа.
- [ ] **Шаг 4:** `/up` health; README runbook: worker, queue, backup БД, PDF ([local или S3](../GLOSSARY.md#nightly-sync-в-object-storage)).
- [ ] **Шаг 5:** если **10b = local на VPS** — документировать nightly backup; если S3 — versioning бакета.
- [ ] **Шаг 6:** финальный pitch EN 60 сек + скриншоты.
- [ ] **Собес:** идемпотентность, 152-ФЗ — устно.
- [ ] `TIME_LOG`.

## Перед ДЗ

- [GLOSSARY.md](../GLOSSARY.md) — webhook, ретраи
- Уроки **06**, **11**, **12**

## Техника

### Возврат до готового PDF

Webhook или Action в Filament → `Payment::refunded`, `Order::cancelled`, не отдавать скачивание.

### Мониторинг (минимум)

- Log channel `integration`
- Uptime `/up`
- Опционально: Sentry — в README как next step

**Критерий:** по README наставник поднимает staging с worker и webhook.

## Финал трека VIN 00–15

Собесы **07–14** в корне — продолжать параллельно.

---

## Справочник

> [06 — privacy](06-deploy-readme-privacy.md). [14 system design](../../../homework/14-interview-system-design-livecoding.md).
