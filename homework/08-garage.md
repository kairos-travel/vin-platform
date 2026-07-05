# Урок 08 — Гараж

**Цель:** ЛК **«Гараж»** — сохранённые ТС; подстановка VIN/ГРЗ/СТС в корзину.

**Перед стартом:** урок **07** (корзина) · [DOMAIN.md](../DOMAIN.md)

---

## Шаг 1 — Миграция `vehicles`

- [ ] `profile_id`, `label`, `vin`, `grz`, `sts` (nullable)

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Migrations | [Migrations](https://laravel.com/docs/migrations) |

**Сделать:**

- Миграция + модель `Vehicle` belongsTo `Profile`

---

## Шаг 2 — CRUD в ЛК

- [ ] Список, добавить, редактировать, удалить — только свои

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Policies | [Authorization](https://laravel.com/docs/authorization) |
| Resource routing | [Routing](https://laravel.com/docs/routing#resource-routes) |

**Сделать:**

- `/account/garage`; `VehiclePolicy` — владелец `profile`

---

## Шаг 3 — «Из гаража» в корзине

- [ ] Выбор ТС → подстановка `input_value`

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Blade components | [Blade Components](https://laravel.com/docs/blade#components) |

**Сделать:**

- Модалка/селект на странице оффера и в корзине

---

## Шаг 4 — «Сохранить в гараж» (опц.)

- [ ] После успешного отчёта — предложение сохранить ТС

**Сделать:**

- Опциональный flow после `Report` `completed`

---

## Шаг 5 — Валидация форматов

- [ ] Form Request для VIN/ГРЗ/СТС

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Validation | [Validation](https://laravel.com/docs/validation) |

**Сделать:**

- Правила в `StoreVehicleRequest` / при добавлении в корзину

**Критерий:** ТС из гаража → корзина → правильный VIN в `order_items`. Snapshot в заказе, не ссылка на `vehicles`.

---

## Собес

- [ ] §15 (нормализация — что хранить в vehicles) — устно

## TIME_LOG

- [ ] Записать часы

## Следующий урок

[09 — поддержка](09-support-tickets.md)

---

## Справочник

> [07 OOP](../../../homework/07-interview-oop.md)
