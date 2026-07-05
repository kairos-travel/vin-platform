# Урок 14 — Тарифы: пакеты по количеству

**Цель:** `/tariffs` — покупка пакетов N отчётов; списание квоты при checkout.

**Перед стартом:** ответ заказчика [QUESTIONS_FOR_CLIENT §0](../QUESTIONS_FOR_CLIENT.md) · [QUESTIONS.md](../QUESTIONS.md) · [GLOSSARY.md](../GLOSSARY.md) · [DOMAIN.md § тарифы](../DOMAIN.md)

---

## Шаг 0 — ERD фаза B

- [ ] OK наставника на схему тарифов **до миграций**

**Прочитать:**

| Тема | Документация |
|------|----------------|
| ERD шаблон | [docs/ERD.md](../docs/ERD.md) |
| Решение заказчика | [QUESTIONS.md §0](../QUESTIONS.md) |

**Сделать:**

- Только **quota** (пакет N отчётов), **без** тарифов по сроку
- Привязка `tariff_plan.service_id`; `profile_tariffs.reports_remaining`

---

## Шаг 1 — Миграции

- [ ] `tariff_plans`, `profile_tariffs`

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Migrations | [Migrations](https://laravel.com/docs/migrations) |

**Сделать:**

```text
tariff_plans: service_id, type=quota, name, price, report_quota
profile_tariffs: profile_id, tariff_plan_id, reports_remaining, status
```

---

## Шаг 2 — Filament `TariffPlan`

- [ ] CRUD планов по услуге

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Filament | [Resources](https://filamentphp.com/docs/panels/resources/getting-started) |

---

## Шаг 3 — Страница `/tariffs`

- [ ] Карточки пакетов; покупка через Paykeeper

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Paykeeper flow | [урок 03](03-orders-paykeeper-queue-integrations.md) |

**Сделать:**

- Отдельный order type или связь `profile_tariff_id` — зафиксировать в коде

---

## Шаг 4 — `TariffGate` при checkout

- [ ] 1 заказ = −1 квота; любой Offer услуги = 1 единица

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Service container | [Service Container](https://laravel.com/docs/container) |

**Сделать:**

- При активном `profile_tariff` — списание или 0 ₽ по правилу 8b

---

## Шаг 5 — Cron `tariffs:expire` (если есть `ends_at`)

- [ ] Для quota — статус `depleted` при 0

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Task Scheduling | [Scheduling](https://laravel.com/docs/scheduling) |

---

## Шаг 6 — Тесты квоты

- [ ] 3 заказа по квоте → 4-й требует оплату

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Testing | [Testing](https://laravel.com/docs/testing) |

**Зафиксировано:** без автопродления; квота только на свою услугу (8b).

---

## TIME_LOG

- [ ] Записать часы

## Следующий урок

[15 — прод](15-production-guest-refunds.md)

---

## Справочник

> [11 Laravel theory 2](../../../homework/11-interview-laravel-theory-2.md)
