# Урок 00 — Окружение: VPS REG.RU + Laravel на сервере

**Цель:** рабочий **staging на VPS** — SSH, домен, nginx, PHP, MySQL, Laravel, **HTTPS**. Код в `projects/vin-platform/` (в git), на сервере — clone в `/var/www/vin-platform`.

**Можно начинать без ответов заказчика** по §0 и 8b — тарифы к **уроку 14**, ERD фазы A (урок 01) — без таблиц тарифов.

**Два пути (выбери один):**

| Путь | Когда |
|------|--------|
| **A. Сервер (рекомендуется)** | шаги 1–10 ниже — работаешь на VPS с урока 00 |
| **B. Локально + VPS «впрок»** | Laravel на Mac (шаг 11B), VPS только заказан; деплой переносишь на шаги 4–10 позже |

Перед стартом: [GLOSSARY.md](../GLOSSARY.md).

## Задание (сдать наставнику)

### Путь A — staging на сервере

- [ ] **Шаг 1:** VPS REG.RU по чеклисту; SSH работает.
- [ ] **Шаг 2:** DNS: A-записи `@` и `www` → IP VPS (`dig` OK).
- [ ] **Шаг 3:** на сервере установлены nginx, PHP 8.3, MySQL, git, composer.
- [ ] **Шаг 4:** Laravel в `/var/www/vin-platform`, `.env`, `key:generate`, `migrate` (пустая БД — OK).
- [ ] **Шаг 5:** nginx отдаёт сайт; в браузере **https://твой-домен.ru** — стартовая Laravel (или http по IP, если домена нет).
- [ ] **Шаг 6:** **HTTPS** (Let's Encrypt) — если домен уже указывает на IP.
- [ ] **Шаг 7:** `php artisan test` на сервере — зелёный.
- [ ] **Шаг 8:** текст 5–10 предложений: путь `GET /` (корневой урок 00 §33).
- [ ] **Шаг 9:** код VIN только в `projects/vin-platform/`, не в корне mentorship.
- [ ] **Собес:** §33, I0 — устно.
- [ ] `TIME_LOG`.

### Путь B — только локально (если VPS ещё не готов)

- [ ] `composer create-project` в `projects/vin-platform/`, `php artisan serve`, тесты зелёные.
- [ ] VPS + DNS + деплой — догоняешь шагами 1–6 **до урока 01**.

---

## Техника — путь A (сервер)

### Шаг 1 — заказ VPS на REG.RU

Разработка на сервере — **VPS / облачный сервер**, **не** виртуальный хостинг.

#### Эталонная конфигурация

| Параметр | Выбор |
|----------|--------|
| **ОС** | Ubuntu **24.04 LTS** |
| **Регион** | Москва-2 (или ДЦ в РФ) |
| **CPU / RAM / диск** | 1 vCPU, **2 GB** RAM, 10 GB NVMe |
| **Публичный IP** | **«Плавающий IP» — ВКЛ** (без него нет доступа из интернета) |
| **Бэкап REG.RU** | выкл на staging |

#### В панели REG.RU

1. VPS → **Своя конфигурация** → Ubuntu 24.04, 2 GB RAM, IP **включён**.
2. SSH-ключ при заказе:
   ```bash
   ssh-keygen -t ed25519 -C "vin-platform"
   cat ~/.ssh/id_ed25519.pub   # вставить в форму
   ```
3. Записать **IP** после статуса «Работает».

#### Первый вход

```bash
ssh root@ВАШ_IP
apt update && apt upgrade -y
uname -a && free -h && df -h
```

---

### Шаг 2 — домен → IP (DNS)

1. REG.RU → **Домены** → твой домен → **Ресурсные записи**.
2. Добавить:

| Имя | Тип | Значение |
|-----|-----|----------|
| `@` | A | `ВАШ_IP` |
| `www` | A | `ВАШ_IP` |

3. Проверка с Mac (подожди 5–30 мин):

```bash
dig +short example.ru A
dig +short www.example.ru A
```

**Без домена:** шаги 5–6 временно по `http://ВАШ_IP`; HTTPS — когда домен готов.

---

### Шаг 3 — стек на Ubuntu (на сервере)

Выполни **под root** (или через `sudo`):

```bash
apt install -y nginx mysql-server git unzip curl \
  php8.3-fpm php8.3-cli php8.3-mysql php8.3-xml php8.3-mbstring \
  php8.3-curl php8.3-zip php8.3-bcmath

curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
composer -V
php -v
```

**MySQL — пользователь для Laravel:**

```bash
mysql
```

```sql
CREATE DATABASE vin_platform CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'vin'@'localhost' IDENTIFIED BY 'СМЕНИ_ПАРОЛЬ';
GRANT ALL ON vin_platform.* TO 'vin'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

**Firewall (опционально, но полезно):**

```bash
ufw allow OpenSSH
ufw allow 'Nginx Full'
ufw enable
```

---

### Шаг 4 — Laravel на сервере

**Вариант 1 — git (рекомендуется):** код на Mac в `projects/vin-platform/`, push в GitHub/GitLab, на сервере:

```bash
mkdir -p /var/www
cd /var/www
git clone https://github.com/ТЫ/vin-platform.git vin-platform
cd vin-platform
composer install --no-dev --optimize-autoloader   # на staging можно без --no-dev
cp .env.example .env
php artisan key:generate
```

**Вариант 2 — создать на сервере** (если репо ещё нет):

```bash
cd /var/www
composer create-project laravel/laravel vin-platform
cd vin-platform
```

**`.env` на сервере (минимум):**

```env
APP_NAME="Vin Platform"
APP_ENV=staging
APP_DEBUG=true
APP_URL=https://example.ru

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vin_platform
DB_USERNAME=vin
DB_PASSWORD=СМЕНИ_ПАРОЛЬ
```

```bash
php artisan migrate   # только стандартные таблицы Laravel — OK
chown -R www-data:www-data /var/www/vin-platform
chmod -R ug+rwx storage bootstrap/cache
```

---

### Шаг 5 — nginx

Файл `/etc/nginx/sites-available/vin-platform`:

```nginx
server {
    listen 80;
    server_name example.ru www.example.ru;
    root /var/www/vin-platform/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
ln -sf /etc/nginx/sites-available/vin-platform /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
nginx -t && systemctl reload nginx
```

Проверка: `http://example.ru` или `http://ВАШ_IP` — страница Laravel.

---

### Шаг 6 — HTTPS (Let's Encrypt)

**Только если** DNS уже указывает на VPS:

```bash
apt install -y certbot python3-certbot-nginx
certbot --nginx -d example.ru -d www.example.ru
```

Certbot сам поправит nginx. Проверка: `https://example.ru`.

Автопродление: `certbot renew --dry-run`.

**Paykeeper webhook** (урок 03+) будет на `https://example.ru/webhooks/paykeeper` — без HTTPS не заработает.

---

### Шаг 7 — тесты на сервере

```bash
cd /var/www/vin-platform
php artisan test
```

---

### Шаг 8 — путь запроса `GET /`

Напиши наставнику 5–10 предложений (см. корневой [homework/00 §33](../../../homework/00-local-environment-routing-tests.md)). На сервере цепочка та же: `public/index.php` → nginx → `php-fpm` → Laravel.

---

### Шаг 9 — граница репозитория

- Учебный код VIN — **`projects/vin-platform/`** в git mentorship (или отдельный repo → clone в `/var/www/vin-platform`).
- Корень `laravel-mentorship/` — доки, homework, interview; **не** смешивать с VIN-приложением.
- Секреты — только `.env` на сервере, не в git.

---

### Работа на сервере дальше (уроки 01+)

| Задача | Как |
|--------|-----|
| Редактировать код | Cursor локально → git push → на VPS `git pull` **или** SSH + nano/vim (неудобно) |
| Миграции | `php artisan migrate` на VPS |
| Очередь | `queue:work` — **урок 05** (systemd или Docker) |
| Деплой обновлений | `git pull && composer install && php artisan migrate --force && php artisan config:cache` |

**Параллельно:** отправь заказчику [QUESTIONS_FOR_CLIENT.md](../QUESTIONS_FOR_CLIENT.md) — тарифы к **уроку 14**.

---

## Техника — путь B (локально)

```bash
cd projects/vin-platform
composer create-project laravel/laravel . --prefer-dist   # если папка пустая
# или: composer install
cp .env.example .env
php artisan key:generate
php artisan serve
php artisan test
```

SQLite для старта: `DB_CONNECTION=sqlite`, `touch database/database.sqlite`.

Деплой на VPS — шаги 1–6 пути A **до начала урока 01**.

---

## Критерии сдачи (путь A)

| Шаг | Доказательство |
|-----|----------------|
| VPS | IP, 2 GB RAM, SSH OK |
| DNS | `dig` → IP |
| Сайт | скрин `https://домен` или `http://IP` — Laravel welcome |
| HTTPS | скрин замка в браузере (если есть домен) |
| Тесты | вывод `php artisan test` |

## Следующий урок

[01 — ERD, миграции, политики](01-domain-migrations-policies.md) — миграции уже на **staging VPS** или локально, как договоритесь с наставником.

---

## Справочник

> Собес: [homework/00 §33, I0](../../../homework/00-local-environment-routing-tests.md). **I0:** `artisan serve` vs nginx+php-fpm — на staging только nginx.
