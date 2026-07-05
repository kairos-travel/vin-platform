# Урок 15 — Прод: возвраты, мониторинг, runbook

**Цель:** коммерческая готовность: возвраты, failed jobs, rate limit, health, runbook.

**Перед стартом:** уроки **06**, **11**, **12** · [GLOSSARY.md](../GLOSSARY.md)

---

## Шаг 1 — Возврат вручную

- [ ] Filament Action + Paykeeper; `Payment::refunded`; отмена Job

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Filament Actions | [Actions](https://filamentphp.com/docs/actions) |
| Queues | [Queues](https://laravel.com/docs/queues) |

**Сделать:**

- Авто-возврат — не в MVP
- До готового PDF — не отдавать download

---

## Шаг 2 — `failed_jobs`

- [ ] Просмотр в Filament или `reports:retry-failed`

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Failed jobs | [Dealing With Failed Jobs](https://laravel.com/docs/queues#dealing-with-failed-jobs) |

---

## Шаг 3 — Rate limiting

- [ ] На webhook и формы заказа

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Rate limiting | [Rate Limiting](https://laravel.com/docs/routing#rate-limiting) |

---

## Шаг 4 — Health + runbook

- [ ] `/up`; README: worker, queue, backup БД + PDF

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Health | [Health Checks](https://laravel.com/docs/deployment#the-health-route) |
| Deployment | [Deployment](https://laravel.com/docs/deployment) |

---

## Шаг 5 — Backup PDF

- [ ] local VPS — nightly backup; S3 — versioning

**Прочитать:**

| Тема | Документация |
|------|----------------|
| GLOSSARY backup | [GLOSSARY.md](../GLOSSARY.md) |

---

## Шаг 6 — Финальный pitch + скриншоты

- [ ] Pitch EN 60 сек

**Критерий:** по README наставник поднимает staging с worker и webhook.

---

## Собес

- [ ] Идемпотентность, 152-ФЗ — устно

## TIME_LOG

- [ ] Записать часы

## Финал трека 00–15

Собесы **07–14** в корне — параллельно.

---

## Справочник

> [06 — privacy](06-deploy-readme-privacy.md) · [14 system design](../../../homework/14-interview-system-design-livecoding.md)
