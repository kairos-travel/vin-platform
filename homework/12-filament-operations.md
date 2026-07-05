# Урок 12 — Filament: операции и поддержка бизнеса

**Цель:** админка для оператора: заказы, отчёты, логи, ручной **retry** Job.

**Перед стартом:** уроки **02**, **09**, **10**

---

## Шаг 1 — Resource `Order`

- [ ] Read-only список, фильтры, relations items/payments

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Filament tables | [Tables](https://filamentphp.com/docs/tables) |
| Relation managers | [Relation Managers](https://filamentphp.com/docs/panels/resources/relation-managers) |

---

## Шаг 2 — Resource `Report`

- [ ] Статус, файл, `meta.partial`

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Filament Resources | [Resources](https://filamentphp.com/docs/panels/resources/getting-started) |

---

## Шаг 3 — Resource `IntegrationLog`

- [ ] Поиск по `order_item_id`, provider

**Сделать:**

- Read-only или ограниченное редактирование

---

## Шаг 4 — Action «Перезапустить генерацию»

- [ ] На `failed` Report → `GenerateReportsJob::dispatch`

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Actions | [Actions](https://filamentphp.com/docs/actions) |
| Queues | [Queues](https://laravel.com/docs/queues) |

---

## Шаг 5 — Action «Отменить заказ»

- [ ] Только `pending_payment` (согласованная политика)

---

## Шаг 6 — Дашборд

- [ ] Счётчики: open tickets, failed reports, orders today

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Widgets | [Widgets](https://filamentphp.com/docs/panels/dashboard) |

**Критерий:** оператор жмёт retry → воркер поднимает статус. Только роль `admin` (`canAccessPanel`).

---

## Собес

- [ ] §42 (админка vs API) — устно

## TIME_LOG

- [ ] Записать часы

## Следующий урок

[13 — API Sanctum](13-api-sanctum.md)

---

## Справочник

> [02-api-sanctum](../../../homework/02-api-sanctum-resources.md)
