# VIN-платформа — код приложения

Учебный + коммерческий Laravel-проект. Доменная модель: [DOMAIN.md](./DOMAIN.md). Уроки **00–15**: [homework/README.md](./homework/README.md) (фаза 1: 00–06, фаза 2: 07–15).

## Перед стартом (урок 00)

Код живёт **только здесь**, не в корне `laravel-mentorship/`.

### 1. Нулевой Laravel в этой папке

Из корня mentorship:

```bash
cd projects/vin-platform

# если папка пустая (кроме DOMAIN.md, homework/, README.md):
composer create-project laravel/laravel . --prefer-dist

# или если Laravel уже создан — только зависимости:
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Откат корневого Laravel (mentorship)

Корневой `app/`, `routes/`, кастомные миграции — **убрать**, оставить чистый каркас наставничества:

```bash
cd /path/to/laravel-mentorship

# удалить учебные артефакты, если появятся снова:
# app/Models/Project.php, Task.php, кастомные migrations, правки routes/web.php

git checkout -- app/ routes/ database/  # только если файлы были в git без нужных изменений
php artisan migrate:fresh   # в КОРНЕ — только если там свой .env для демо; иначе не трогать
```

**Правило:** уроки **00–06 VIN** выполняются из `projects/vin-platform/`; `php artisan` — из этой папки.

### 3. ERD (перед уроком 01)

Шаблон: [docs/ERD.md](./docs/ERD.md) — фаза A без тарифов; фаза B после ответа заказчика.

### 4. Окружение VIN

```bash
cd projects/vin-platform
php artisan serve
php artisan test
```

`.env` — отдельный от корня. Paykeeper, Tronk и др. — только в `.env` этой папки (не коммитить).

### 5. VPS REG.RU + staging (урок 00)

Полная настройка на сервере: VPS, DNS, nginx, PHP, MySQL, Laravel, HTTPS.

```bash
ssh root@ВАШ_IP
# дальше — шаги 3–6 в homework/00-local-environment.md
```

Код: git push с Mac → `git pull` на `/var/www/vin-platform`. Локальный `php artisan serve` — опционально (путь B в уроке 00).

## Стек (зафиксировано)

- Laravel + **Breeze** (Blade + Tailwind)
- **Filament** — админка каталога
- Очередь: database или redis (урок 05)
- PDF: `barryvdh/laravel-dompdf` или аналог (урок 03)

## Ссылки

- [DOMAIN.md](./DOMAIN.md) — сущности, решения, паттерны
- [Цепочка API](https://docs.google.com/spreadsheets/d/1XMaa4ime6GRpaHGWuVBZxS6OzGMP6Itk8Oz_puXxkXc/edit?gid=0#gid=0)
- [Figma](https://www.figma.com/design/lH5KkodmwnpGfS3pv1SzFD/Vin-%D0%BE%D1%82%D1%87%D0%B5%D1%82?node-id=0-1)
