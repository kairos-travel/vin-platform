# Урок 00 — Окружение: VPS REG.RU + Laravel на сервере

**Цель:** staging на VPS — SSH, DNS, nginx, PHP, MySQL, Laravel, **HTTPS**. Код в `projects/vin-platform/`.

**Можно начинать без ответов заказчика** по тарифам (урок 14) и ERD (урок 01).

**Два пути:**

| Путь | Когда |
|------|--------|
| **A — сервер (рекомендуется)** | шаги 1–9 ниже |
| **B — локально** | шаг 10; VPS догоняешь шагами 1–6 до урока 01 |

**Перед стартом:** [GLOSSARY.md](../GLOSSARY.md)

---

## Шаг 1 — VPS на REG.RU

- [ ] SSH работает; Ubuntu 24.04, 2 GB RAM, публичный IP

**Прочитать:**

| Тема | Ссылка |
|------|--------|
| SSH keys | [GitHub SSH docs](https://docs.github.com/en/authentication/connecting-to-github-with-ssh) |
| VPS vs shared hosting | [GLOSSARY.md](../GLOSSARY.md) |

**Сделать:**

- REG.RU → VPS → Ubuntu 24.04, 2 GB, **плавающий IP включён**
- `ssh-keygen -t ed25519`; ключ в панели
- `ssh root@IP` → `apt update && apt upgrade -y`

---

## Шаг 2 — DNS → IP

- [ ] `dig` показывает IP VPS для `@` и `www`

**Прочитать:**

| Тема | Ссылка |
|------|--------|
| DNS A-record | [REG.RU help](https://www.reg.ru/support/) или документация регистратора |

**Сделать:**

- A-записи `@` и `www` → IP VPS
- `dig +short example.ru A` с Mac (подождать 5–30 мин)
- Без домена: временно по `http://IP`

---

## Шаг 3 — Стек на Ubuntu

- [ ] nginx, PHP 8.3, MySQL, git, composer

**Прочитать:**

| Тема | Ссылка |
|------|--------|
| Laravel server requirements | [Server Requirements](https://laravel.com/docs/deployment#server-requirements) |
| nginx + PHP-FPM | [Deployment — nginx](https://laravel.com/docs/deployment#nginx) |

**Сделать:**

```bash
apt install -y nginx mysql-server git unzip curl \
  php8.3-fpm php8.3-cli php8.3-mysql php8.3-xml php8.3-mbstring \
  php8.3-curl php8.3-zip php8.3-bcmath
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

- MySQL: БД `vin_platform`, user `vin`, пароль свой
- `ufw allow OpenSSH && ufw allow 'Nginx Full' && ufw enable` (опц.)

---

## Шаг 4 — Laravel на сервере

- [ ] `/var/www/vin-platform`, `.env`, `migrate`, права `storage`

**Прочитать:**

| Тема | Ссылка |
|------|--------|
| Deployment | [Laravel Deployment](https://laravel.com/docs/deployment) |
| `.env` | [Configuration](https://laravel.com/docs/configuration#environment-configuration) |

**Сделать:**

- `git clone` в `/var/www/vin-platform` (или `create-project`)
- `.env`: `APP_ENV=staging`, `APP_URL`, MySQL
- `composer install`, `php artisan key:generate`, `php artisan migrate`
- `chown -R www-data:www-data` + права на `storage`, `bootstrap/cache`

---

## Шаг 5 — nginx

- [ ] Сайт открывается в браузере

**Прочитать:**

| Тема | Ссылка |
|------|--------|
| nginx config | [Deployment — nginx](https://laravel.com/docs/deployment#nginx) |

**Сделать:**

- `/etc/nginx/sites-available/vin-platform` → `root .../public`, `try_files`, php-fpm socket
- `nginx -t && systemctl reload nginx`
- Проверка: `http://домен` или `http://IP`

---

## Шаг 6 — HTTPS (Let's Encrypt)

- [ ] `https://домен` с замком в браузере

**Прочитать:**

| Тема | Ссылка |
|------|--------|
| Certbot | [certbot.eff.org](https://certbot.eff.org/) |

**Сделать:**

- Только если DNS уже на VPS
- `apt install certbot python3-certbot-nginx`
- `certbot --nginx -d example.ru -d www.example.ru`
- `certbot renew --dry-run`

*Paykeeper webhook (урок 03) требует HTTPS.*

---

## Шаг 7 — Тесты на сервере

- [ ] `php artisan test` — зелёный

**Прочитать:**

| Тема | Ссылка |
|------|--------|
| Testing | [Testing](https://laravel.com/docs/testing) |

---

## Шаг 8 — Путь запроса `GET /`

- [ ] 5–10 предложений наставнику

**Прочитать:**

| Тема | Ссылка |
|------|--------|
| Request lifecycle | [Request Lifecycle](https://laravel.com/docs/lifecycle) |
| Корневой урок §33 | [00-local-environment-routing-tests.md](../../../homework/00-local-environment-routing-tests.md) |

**Сделать:**

- Описать: nginx → `public/index.php` → Laravel → route → response

---

## Шаг 9 — Граница репозитория

- [ ] Код VIN только в `projects/vin-platform/`; `.env` не в git

**Сделать:**

- Mentorship root — docs/homework; VIN-app — отдельная папка / repo
- Секреты только на сервере

**Дальше (уроки 01+):** локально код → `git push` → на VPS `git pull`; очередь — урок 05.

---

## Шаг 10 — Путь B (только локально)

- [ ] `composer install`, `php artisan serve`, тесты зелёные

**Прочитать:**

| Тема | Ссылка |
|------|--------|
| Installation | [Installation](https://laravel.com/docs/installation) |

**Сделать:**

```bash
cd projects/vin-platform
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
php artisan test
```

- VPS шаги 1–6 — **до урока 01**

---

## Собес

- [ ] §33, I0 — устно · `artisan serve` vs nginx+php-fpm — на staging только nginx

## TIME_LOG

- [ ] Записать часы

## Критерии сдачи (путь A)

| | Доказательство |
|---|----------------|
| VPS | IP, SSH OK |
| DNS | `dig` → IP |
| Сайт | скрин Laravel welcome |
| HTTPS | замок в браузере (если есть домен) |
| Тесты | `php artisan test` |

## Следующий урок

[01 — ERD, миграции, политики](01-domain-migrations-policies.md)

---

## Справочник

> [homework/00 §33, I0](../../../homework/00-local-environment-routing-tests.md) · [QUESTIONS_FOR_CLIENT.md](../QUESTIONS_FOR_CLIENT.md)
