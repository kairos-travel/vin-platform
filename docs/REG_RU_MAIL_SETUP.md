# Почта для сброса пароля

Проект уже на сервере: `/var/www/vin-platform`, домен `iz-agent.ru` → VPS.  
Осталось: ящик в REG.RU + строки в `.env` на сервере.

---

## 1. REG.RU — создать ящик (не на VPS)

reg.ru → **Хостинг** → **Войти в панель** → **Почта** → **Почтовые ящики** → **Создать**

- `noreply@iz-agent.ru`
- пароль — запомнить

Ящик → **Настройки почтовых программ** → SMTP: `mail.hosting.reg.ru`, порт `587`

---

## 2. Сервер — `.env`

```bash
ssh root@cv7496931
nano /var/www/vin-platform/.env
```

Добавить/проверить:

```env
APP_URL=https://iz-agent.ru

MAIL_MAILER=smtp
MAIL_HOST=mail.hosting.reg.ru
MAIL_PORT=587
MAIL_SCHEME=smtp
MAIL_USERNAME=noreply@iz-agent.ru
MAIL_PASSWORD=пароль_от_ящика
MAIL_FROM_ADDRESS=noreply@iz-agent.ru
MAIL_FROM_NAME="${APP_NAME}"
```

```bash
cd /var/www/vin-platform
php artisan config:clear
php artisan config:cache
```

---

## 3. Проверка на сервере

```bash
cd /var/www/vin-platform
php artisan tinker
```

```php
Mail::raw('test', fn ($m) => $m->to('ваш@gmail.com')->subject('test'));
```

Сайт → **Забыли пароль?** → email из БД.

---

## Не работает

| Проблема | Действие |
|----------|----------|
| `tls` scheme not supported | `MAIL_SCHEME=smtp` (не `tls`) |
| Auth failed | логин = полный email, пароль от ящика |
| Ссылка localhost | `APP_URL=https://iz-agent.ru` |
| Спам | reg.ru → Домен → DNS → SPF/DKIM |
