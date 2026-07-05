# Урок 06 — Деплой, README EN, политика ПДн

**Цель:** портфолио + staging: README EN, `/privacy`, согласие при регистрации, UI удаления аккаунта.

**Перед стартом:** уроки **00**, **05** · [DOMAIN.md](../DOMAIN.md) — решения #5, #16 · корневой [06-deploy-portfolio-readme.md](../../../homework/06-deploy-portfolio-readme.md)

---

## Шаг 1 — README.md (EN)

- [ ] What it does, stack, setup, Disclaimer (no real VIN/PII in demo)

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Структура README | [06-deploy-portfolio-readme.md](../../../homework/06-deploy-portfolio-readme.md) |

**Сделать:**

- Stack: Blade, Tailwind, Filament, Paykeeper, queues, PDF pipeline
- Setup local + staging; disclaimer

---

## Шаг 2 — Страница `/privacy` (RU)

- [ ] Политика ПДн по 152-ФЗ

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Blade view | [Views](https://laravel.com/docs/views) |
| Решения проекта | [DOMAIN.md](../DOMAIN.md) |

**Сделать:**

- Оператор, цели, основания (согласие + договор), сроки, права субъекта, контакт
- Передача провайдерам API (Tronk и др.)

---

## Шаг 3 — Согласие при регистрации

- [ ] Чекбокс + ссылка на `/privacy`

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Validation | [Validation](https://laravel.com/docs/validation) |
| Breeze registration | [Laravel Breeze (11.x)](https://laravel.com/docs/11.x/starter-kits#laravel-breeze) |

**Сделать:**

- Обязательный checkbox `accepted` на форме register

---

## Шаг 4 — UI удаления профиля

- [ ] Кнопка → `ProfilePolicy::delete` → `anonymize()` → logout

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Policies | [Authorization](https://laravel.com/docs/authorization) |
| Soft delete | [Soft Deleting](https://laravel.com/docs/eloquent#soft-deleting) |

**Сделать:**

- Подтверждение в UI; вызов `anonymize()` из урока 01
- Logout после удаления

---

## Шаг 5 — Staging актуален

- [ ] `APP_URL`, HTTPS, `config:cache` на VPS

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Deployment | [Deployment](https://laravel.com/docs/deployment) |
| Урок 00 | [00-local-environment.md](00-local-environment.md) |

**Сделать:**

- Проверить `/var/www/vin-platform`, certbot, `php artisan test` на сервере

---

## Шаг 6 — Pitch EN 30 сек

- [ ] Устно: marketplace, Laravel, Paykeeper, queued pipeline

**Сделать:**

- Текст на английском; финальный EN commit

**Критерий:** наставник читает `/privacy` и README без вопросов «а где согласие?».

---

## Собес

- [ ] GDPR/152-ФЗ basics — устно

## TIME_LOG

- [ ] Записать часы

## Следующий урок

[07 — корзина и checkout](07-cart-checkout.md)

---

## Справочник

> [MENTORSHIP.md](../../../MENTORSHIP.md) · [100 вопросов](../../../interview/100-questions-middle.md)
