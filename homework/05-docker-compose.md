# Урок 05 — Docker Compose

**Цель:** `docker compose up` — app + db + **queue worker**; VIN-путь в контейнере.

**Перед стартом:** урок **03** с очередью · [урок 00](00-local-environment.md) (VPS) · корневой [05-docker-compose.md](../../../homework/05-docker-compose.md)

---

## Шаг 1 — docker-compose.yml

- [ ] Сервисы: app/php-fpm, nginx, mysql, опционально redis

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Docker Compose | [Compose file reference](https://docs.docker.com/compose/compose-file/) |
| Эталон урока | [05-docker-compose.md](../../../homework/05-docker-compose.md) |

**Сделать:**

- `docker-compose.yml` в `projects/vin-platform/`
- Volume на код; PHP 8.3+

---

## Шаг 2 — Сервис queue

- [ ] `php artisan queue:work` в отдельном контейнере

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Laravel queues | [Queues](https://laravel.com/docs/queues) |
| Running worker | [Running Queue Worker](https://laravel.com/docs/queues#running-the-queue-worker) |

**Сделать:**

- Сервис `queue`: тот же image, `command: queue:work --sleep=1`
- `DB_HOST=db`, `QUEUE_CONNECTION=database` или `redis`

---

## Шаг 3 — README-фрагмент

- [ ] Инструкция: migrate + worker в Docker

**Прочитать:**

- Корневой урок 05 — INF1–INF5

**Сделать:**

- Команды `docker compose up`, `migrate`, проверка webhook

---

## Шаг 4 — Демо end-to-end

- [ ] Webhook в контейнере → отчёт без ручного `queue:work` на хосте

**Сделать:**

- Тестовый платёж → PDF `completed` только через compose

**Критерий:** наставник повторяет запуск по README.

---

## Собес

- [ ] D1–D4 из корневого [05](../../../homework/05-docker-compose.md) — устно

## TIME_LOG

- [ ] Записать часы

## Следующий урок

[06 — деплой, README, ПДн](06-deploy-readme-privacy.md)

---

## Справочник

> [05 Docker](../../../homework/05-docker-compose.md)
