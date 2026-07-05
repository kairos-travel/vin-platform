# Урок 09 — Служба поддержки

**Цель:** ЛК **«Служба поддержки»** — тикеты, статусы, переписка; ответы в Filament.

**Перед стартом:** уроки **02** (Filament), **03** (заказы)

---

## Шаг 1 — `support_tickets`

- [ ] `profile_id`, `subject`, `status`, `order_id` nullable

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Migrations | [Migrations](https://laravel.com/docs/migrations) |

**Сделать:**

- Статусы: `open`, `answered`, `closed`

---

## Шаг 2 — `support_messages`

- [ ] `ticket_id`, `user_id` nullable, `body`

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Relationships | [Eloquent Relationships](https://laravel.com/docs/eloquent-relationships) |

**Сделать:**

- `user_id` null = ответ оператора из Filament

---

## Шаг 3 — ЛК клиента

- [ ] Создать тикет, список, переписка, ответ клиента

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Controllers | [Controllers](https://laravel.com/docs/controllers) |

**Сделать:**

- CRUD тикетов в ЛК; только свои

---

## Шаг 4 — Filament (admin)

- [ ] Просмотр, ответ, смена статуса

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Filament Resources | [Filament Resources](https://filamentphp.com/docs/panels/resources/getting-started) |
| Relation managers | [Relation Managers](https://filamentphp.com/docs/panels/resources/relation-managers) |

**Сделать:**

- Resource `SupportTicket`; роль `admin` only (не отдельная support в MVP)

---

## Шаг 5 — Создание с заказа/отчёта

- [ ] «Проблема с заказом» с предзаполненным `order_id`

**Сделать:**

- Ссылка/кнопка из ЛК заказов и отчётов

**Критерий:** клиент создаёт тикет → оператор отвечает в Filament → клиент видит ответ.

**Policy:** `SupportTicketPolicy` — view/create только владелец `profile`.

---

## Собес

- [ ] §37 (auth), §9 (SupportService vs контроллер) — устно

## TIME_LOG

- [ ] Записать часы

## Следующий урок

[10 — живые интеграции](10-integrations-live.md)

---

## Справочник

> [12 Bitrix](../../../homework/12-interview-bitrix.md)
