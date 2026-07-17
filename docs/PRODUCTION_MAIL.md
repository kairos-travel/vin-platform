# Staging / Production: деплой и почта

Инструкция для **VPS** (REG.RU, Ubuntu): как выкатывать код и настроить отправку писем (сброс пароля, позже — verify email и уведомления).

**Путь на сервере (ориентир):** `/var/www/vin-platform`  
**Домен (ориентир):** `https://iz-agent.ru`

См. также:

- [00-local-environment.md](../homework/00-local-environment.md) — первичная настройка VPS, nginx, HTTPS
- [PASSWORD_RESET_EMAIL.md](./PASSWORD_RESET_EMAIL.md) — текст и вид письма сброса пароля
- [REG_RU_MAIL_SETUP.md](./REG_RU_MAIL_SETUP.md) — **подробно:** ящик в ispmanager, откуда брать каждый `MAIL_*`
- [Laravel Deployment](https://laravel.com/docs/deployment)

---

## Содержание

1. [Первичный деплой](#часть-1-первичный-деплой)
2. [Обновление после `git push`](#часть-2-обновление-после-git-push)
3. [`.env` на сервере](#часть-3-env-на-сервере)
4. [Почта (SMTP)](#часть-4-почта-smtp)
5. [Проверка после деплоя](#часть-5-проверка-после-деплоя)
6. [Типичные проблемы](#часть-6-типичные-проблемы)

---

## Часть 1. Первичный деплой

*Если VPS, nginx и HTTPS уже подняты (урок 00) — переходите к [части 2](#часть-2-обновление-после-git-push).*

### 1.1. Стек на сервере

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx mysql-server git unzip curl \
  php8.3-fpm php8.3-cli php8.3-mysql php8.3-xml php8.3-mbstring \
  php8.3-curl php8.3-zip php8.3-bcmath nodejs npm
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

MySQL: БД `vin_platform`, пользователь `vin`, свой пароль.

### 1.2. Клонирование проекта

```bash
sudo mkdir -p /var/www
cd /var/www
sudo git clone <URL-репозитория> vin-platform
cd vin-platform/projects/vin-platform   # если repo = mentorship monorepo
# или cd vin-platform                 # если отдельный repo только VIN-app
```

> Код приложения живёт в `projects/vin-platform/`. Ниже все команды — **из этой папки**.

### 1.3. `.env` и ключ приложения

```bash
cp .env.example .env
nano .env
php artisan key:generate
```

Минимум для staging — см. [часть 3](#часть-3-env-на-сервере).

### 1.4. Зависимости, миграции, фронт

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
```

`npm run build` создаёт `public/build/` — **без этого** на prod не будет CSS/JS (Vite).

### 1.5. Права

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache
sudo chmod 640 .env
```

### 1.6. nginx

Файл `/etc/nginx/sites-available/vin-platform`:

```nginx
server {
    listen 80;
    server_name iz-agent.ru www.iz-agent.ru;
    root /var/www/vin-platform/projects/vin-platform/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

> Поправьте `root`, если путь к проекту другой.

```bash
sudo ln -sf /etc/nginx/sites-available/vin-platform /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
```

### 1.7. HTTPS (Let's Encrypt)

Только когда DNS `@` и `www` указывают на IP VPS:

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d iz-agent.ru -d www.iz-agent.ru
sudo certbot renew --dry-run
```

После certbot в `.env`: `APP_URL=https://iz-agent.ru`.

### 1.8. Кэш конфигурации

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Часть 2. Обновление после `git push`

Типичный цикл: правки локально → commit → push → на сервере pull.

### 2.1. Скрипт деплоя (каждый релиз)

SSH на VPS:

```bash
cd /var/www/vin-platform/projects/vin-platform

git pull origin main

composer install --no-dev --optimize-autoloader

# если менялись CSS/JS/Blade с @vite:
npm ci
npm run build

php artisan migrate --force

php artisan config:cache
php artisan route:cache
php artisan view:cache

# опционально, если кэш «залип»:
# php artisan optimize:clear && php artisan config:cache && ...

sudo systemctl reload php8.3-fpm
```

### 2.2. Режим обслуживания (опционально)

На время миграций с даунтаймом:

```bash
php artisan down --secret="deploy-2026"
# ... migrate, build ...
php artisan up
```

Сайт доступен по `https://iz-agent.ru/deploy-2026` во время `down`.

### 2.3. Что **не** коммитить

| Файл / папка | Где живёт |
|--------------|-----------|
| `.env` | только на сервере |
| `vendor/` | `composer install` на VPS |
| `node_modules/` | `npm ci` на VPS |
| `public/build/` | `npm run build` на VPS *(или CI)* |
| `storage/logs/*` | генерируется на сервере |

### 2.4. Очередь (позже, урок 05)

Сброс пароля сейчас идёт **синхронно** — worker не нужен.  
Когда появятся Jobs в очереди:

```bash
php artisan queue:work --sleep=3 --tries=3
```

Через **supervisor** или systemd — см. [урок 05](../homework/05-docker-compose.md).

---

## Часть 3. `.env` на сервере

Пример для **staging/production**:

```env
APP_NAME="БазаБаза"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://iz-agent.ru

APP_LOCALE=ru
APP_FALLBACK_LOCALE=ru

LOG_CHANNEL=stack
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vin_platform
DB_USERNAME=vin
DB_PASSWORD=...

SESSION_DRIVER=database
SESSION_LIFETIME=120

QUEUE_CONNECTION=database
CACHE_STORE=database

# Почта — часть 4
MAIL_MAILER=smtp
MAIL_HOST=smtp.reg.ru
MAIL_PORT=587
MAIL_SCHEME=smtp
MAIL_USERNAME=noreply@iz-agent.ru
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=noreply@iz-agent.ru
MAIL_FROM_NAME="${APP_NAME}"
```

| Переменная | Зачем |
|------------|--------|
| `APP_DEBUG=false` | не светить stack trace |
| `APP_URL=https://...` | ссылки в письмах, asset URL |
| `LOG_LEVEL=warning` | меньше шума в логах |
| `MAIL_*` | реальная отправка писем |

После **любой** правки `.env`:

```bash
php artisan config:clear
php artisan config:cache
```

---

## Часть 4. Почта (SMTP)

Локально письма ловятся в **Mailpit** или `MAIL_MAILER=log`. На сервере нужен **реальный SMTP**.

### 4.1. Как работает сброс пароля

```
POST /forgot-password  (email в поле login)
        ↓
Password::sendResetLink()
        ↓
User::sendPasswordResetNotification()
        ↓
App\Notifications\ResetPasswordNotification
        ↓
resources/views/emails/reset-password.blade.php
        ↓
config/mail.php → SMTP
```

Письмо уходит **сразу** (без очереди).

### 4.2. Чеклист перед настройкой почты

| # | Что | Зачем |
|---|-----|--------|
| 1 | **HTTPS** и верный `APP_URL` | ссылка в письме |
| 2 | Ящик на домене, напр. `noreply@iz-agent.ru` | отправитель |
| 3 | DNS: **SPF**, **DKIM** | не попадать в спам |
| 4 | Пользователь в БД с реальным **email** | иначе некуда слать |

### 4.3. Создать ящик и заполнить `MAIL_*`

**REG.RU (хостинг + ispmanager):** пошагово — в отдельном файле **[REG_RU_MAIL_SETUP.md](./REG_RU_MAIL_SETUP.md)** (создание `noreply@iz-agent.ru`, откуда каждое поле в `.env`).

Кратко: ispmanager → **Почта → Почтовые ящики → Создать** → параметры SMTP из «Настройки почтовых программ».

**Другие провайдеры:**

- **Yandex 360** — `smtp.yandex.ru`, порт 465 (`smtps`) или 587 (`tls`), пароль приложения.
- **Mailgun / Postmark / SendGrid / SES** — SMTP или API из кабинета.

### 4.4. DNS

| Запись | Назначение |
|--------|------------|
| **SPF** (TXT) | кто может слать от имени домена |
| **DKIM** (TXT) | подпись писем |
| **DMARC** (TXT) | политика (можно позже) |

```bash
dig +short TXT iz-agent.ru
dig +short TXT _dmarc.iz-agent.ru
```

### 4.5. Примеры `MAIL_*` в `.env`

**REG.RU:** полный разбор полей — [REG_RU_MAIL_SETUP.md](./REG_RU_MAIL_SETUP.md). Минимальный блок:

```env
MAIL_MAILER=smtp
MAIL_HOST=mail.hosting.reg.ru
MAIL_PORT=587
MAIL_SCHEME=smtp
MAIL_USERNAME=noreply@iz-agent.ru
MAIL_PASSWORD=пароль_из_ispmanager
MAIL_FROM_ADDRESS=noreply@iz-agent.ru
MAIL_FROM_NAME="${APP_NAME}"
```

**Yandex (порт 465):**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.yandex.ru
MAIL_PORT=465
MAIL_SCHEME=smtps
MAIL_USERNAME=noreply@iz-agent.ru
MAIL_PASSWORD=пароль_приложения
MAIL_FROM_ADDRESS=noreply@iz-agent.ru
MAIL_FROM_NAME="БазаБаза"
```

**На prod не использовать:**

```env
MAIL_MAILER=log
MAIL_MAILER=array
```

### 4.6. Проверка почты

**Tinker:**

```bash
php artisan tinker
```

```php
Mail::raw('Test from VIN Platform', function ($m) {
    $m->to('ваш-email@gmail.com')->subject('Mail test');
});
```

**Через сайт:**

1. `https://iz-agent.ru/forgot-password`
2. Email существующего пользователя (не телефон)
3. Проверить входящие и **спам**
4. Ссылка: `https://iz-agent.ru/reset-password/...?email=...`

**Логи:**

```bash
tail -f storage/logs/laravel.log
```

| Симптом | Что проверить |
|---------|----------------|
| Connection timeout | `MAIL_HOST`, firewall, порт 587/465 |
| Authentication failed | логин/пароль, пароль приложения |
| SSL/TLS error | `tls` для 587, `smtps` для 465 |
| Письмо в спам | SPF, DKIM, `MAIL_FROM` на домене |
| Ссылка на localhost | `APP_URL` |
| «Отправили», письма нет | `MAIL_MAILER=log`, неверный email в БД |

### 4.7. Безопасность

- Пароль SMTP только в `.env`, не в Git
- `chmod 640 .env`
- Ящик `noreply@` — только исходящая почта

---

## Часть 5. Проверка после деплоя

Чеклист «всё живо»:

```bash
cd /var/www/vin-platform/projects/vin-platform

php artisan about          # env, drivers
php artisan migrate:status
php artisan test           # если есть тесты на staging
curl -I https://iz-agent.ru
curl -I https://iz-agent.ru/favicon.svg
```

В браузере:

- [ ] Главная открывается, есть стили (не «голый» HTML)
- [ ] Favicon — красная звезда БазаБаза
- [ ] `/login`, `/register` работают
- [ ] `/forgot-password` → письмо приходит
- [ ] HTTPS, нет mixed content

Health (Laravel 11+):

```bash
curl https://iz-agent.ru/up
```

---

## Часть 6. Типичные проблемы

| Проблема | Решение |
|----------|---------|
| Белая страница, 500 | `storage/logs/laravel.log`, права на `storage/` |
| Нет CSS/JS | `npm run build`, проверить `public/build/manifest.json` |
| `Vite manifest not found` | не запускали `npm run build` на сервере |
| Старый `.env` после правки | `php artisan config:clear && php artisan config:cache` |
| 419 CSRF на формах | `SESSION_DOMAIN`, cookies только по HTTPS |
| nginx 404 на все роуты | `root` должен указывать на `.../public` |
| Permission denied storage | `chown www-data`, `chmod ug+rwx storage bootstrap/cache` |

---

## Краткая шпаргалка

```bash
# === первый раз ===
cd /var/www/.../vin-platform
cp .env.example .env && nano .env
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan key:generate && php artisan migrate --force
php artisan config:cache
sudo chown -R www-data:www-data storage bootstrap/cache

# === каждый деплой ===
git pull
composer install --no-dev --optimize-autoloader
npm ci && npm run build          # если менялся фронт
php artisan migrate --force
php artisan config:cache
sudo systemctl reload php8.3-fpm

# === проверка почты ===
php artisan tinker   # Mail::raw(...)
# затем /forgot-password в браузере
```

---

## Связанные файлы

| Файл | Роль |
|------|------|
| `.env.example` | шаблон переменных |
| `config/mail.php` | драйверы почты |
| `app/Notifications/ResetPasswordNotification.php` | письмо сброса |
| `resources/views/emails/reset-password.blade.php` | шаблон письма |
| `routes/auth.php` | auth-роуты |
| `public/build/` | собранный Vite (генерируется на deploy) |
| [PASSWORD_RESET_EMAIL.md](./PASSWORD_RESET_EMAIL.md) | перевод и дизайн письма |
| [00-local-environment.md](../homework/00-local-environment.md) | VPS, DNS, certbot |

---

## Позже

- **Verify email** — те же `MAIL_*`
- **Очередь для писем** — `ShouldQueue` + supervisor
- **Paykeeper webhook** — обязателен HTTPS (урок 03)
- **CI/CD** — GitHub Actions: test → build → deploy по SSH
