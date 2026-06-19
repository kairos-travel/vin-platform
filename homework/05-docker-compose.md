# Урок 05 — Docker Compose

**Цель:** `docker compose up` поднимает app + db + **queue worker**; VIN-путь работает в контейнере.

## Задание (сдать наставнику)

- [ ] **Шаг 1:** `docker-compose.yml` в `projects/vin-platform/`: php-fpm (или app), nginx, mysql/postgres, redis (опционально).
- [ ] **Шаг 2:** сервис `queue` с `php artisan queue:work`.
- [ ] **Шаг 3:** README-фрагмент: как запустить migrate + worker.
- [ ] **Шаг 4:** после тестового webhook в контейнере отчёт появляется без ручного `queue:work` на хосте.
- [ ] **Собес:** D1–D4 из корневого [05](../../../homework/05-docker-compose.md) — устно.
- [ ] `TIME_LOG`.

## Перед ДЗ

- Корневой [05-docker-compose.md](../../../homework/05-docker-compose.md) — эталон по шагам
- Урок **03** с очередью
- **VPS:** поднят на [уроке 00](00-local-environment.md) — nginx, PHP, MySQL; compose можно гонять **локально** или на том же VPS

## Техника

Минимальный compose:

- `app` — PHP 8.3+, volume на код
- `nginx` — прокси на `public/`
- `db` — MySQL 8
- `queue` — тот же image, command: `queue:work --sleep=1`

`.env.docker` или переменные в compose для `DB_HOST=db`, `QUEUE_CONNECTION=database` или `redis`.

**Критерий:** наставник повторяет запуск по вашему README.

## Следующий урок

[06 — деплой, README, ПДн](06-deploy-readme-privacy.md)

---

## Справочник

> [05 Docker](../../../homework/05-docker-compose.md) — INF1–INF5.
