# Урок 14 — Тарифы: по дням и по количеству

**Цель:** блок ТЗ «Тарифы» — **два типа** продуктов на `/tariffs`:

1. **По дням** — доступ на 7 / 30+ дней (безлимит или скидка на услуги в период).
2. **По количеству** — пакет из N отчётов (списание 1 за заказ).

Разовые покупки через Offer + корзину **остаются**; тариф меняет цену или списывает квоту.

**Термины:** [GLOSSARY.md](../GLOSSARY.md). Решение п.8: [DOMAIN.md](../DOMAIN.md#как-работают-тарифы-п8).

## Задание (сдать наставнику)

- [ ] **Шаг 0:** ответ заказчика по [QUESTIONS_FOR_CLIENT §0](../QUESTIONS_FOR_CLIENT.md) получен; **ERD фаза B** — дополнить [docs/ERD.md](../docs/ERD.md) таблицами тарифов; OK наставника.
- [ ] **Шаг 1:** миграции `tariff_plans`, `profile_tariffs` (по **своей** ERD).
- [ ] **Шаг 2:** Filament CRUD `TariffPlan` — тип `duration` | `quota`, поля по типу.
- [ ] **Шаг 3:** страница `/tariffs` — карточки обоих типов; покупка через Paykeeper (отдельный `order` с `type=tariff` или связь `profile_tariff_id`).
- [ ] **Шаг 4:** `TariffGate` при checkout: если активен тариф по дням → скидка/0 ₽; если по квоте → `reports_remaining--`.
- [ ] **Шаг 5:** cron `tariffs:expire` — `ends_at` прошло → статус `expired`.
- [ ] **Шаг 6:** тесты: квота 3 → 3 заказа без доплаты → 4-й требует оплату; duration — заказ в срок ok, после `ends_at` — полная цена.
- [ ] `TIME_LOG`.

## Схема БД

```text
tariff_plans
  - type: duration | quota
  - name, price
  - duration_days (nullable)     # для duration
  - report_quota (nullable)      # для quota
  - allowed_service_ids (json) # null = все услуги

profile_tariffs
  - profile_id, tariff_plan_id
  - starts_at, ends_at (nullable)   # duration
  - reports_remaining (nullable)    # quota
  - status: active | expired | depleted
```

## Как это работает для пользователя

### Тариф «30 дней» (duration)

1. Покупает план на `/tariffs` → Paykeeper → `profile_tariffs.ends_at = now() + 30 days`.
2. В период добавляет VIN в корзину → checkout → **0 ₽** или скидка (правило в `TariffGate`).
3. После `ends_at` — снова обычные цены Offer.

### Тариф «10 отчётов» (quota)

1. Покупает пакет → `reports_remaining = 10`.
2. Каждый **оплаченный** заказ (или завершённый отчёт — зафиксируй в коде) → `--`.
3. При `0` — `depleted`, checkout по полной цене.

### Оба тарифа сразу

Допустимо: duration даёт скидку, quota списывается первой — **правило приоритета** опиши в `TariffGate` (наставник проверит на code review).

## Зафиксировано

- **Автопродление:** нет в MVP — клиент сам покупает тариф снова.
- **8b (квота):** см. [QUESTIONS.md](../QUESTIONS.md) — на что тратится пакет «N отчётов».

## Следующий урок

[15 — прод](15-production-guest-refunds.md)

---

## Справочник

> [11 Laravel theory 2](../../../homework/11-interview-laravel-theory-2.md).
