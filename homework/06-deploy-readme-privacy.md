# Урок 06 — Деплой, README EN, политика ПДн

**Цель:** проект готов к портфолио и коммерческому staging: README на английском, политика обработки ПДн (152-ФЗ), дисклеймеры, pitch VIN-платформы.

## Задание (сдать наставнику)

- [ ] **Шаг 1:** `README.md` (EN) — what it does, stack, setup, **Disclaimer** (no real VIN/PII in demo).
- [ ] **Шаг 2:** страница `/privacy` (RU) — политика ПДн: оператор, цели, основания (согласие + договор), сроки, права субъекта, контакт.
- [ ] **Шаг 3:** при регистрации — чекбокс согласия со ссылкой на `/privacy`.
- [ ] **Шаг 4:** UI удаления профиля → `anonymize()` (урок 01).
- [ ] **Шаг 5:** staging на VPS с [урока 00](../homework/00-local-environment.md) актуален: проверь `APP_URL`, HTTPS, `php artisan config:cache` после изменений.
- [ ] **Шаг 6:** pitch 30 сек EN: *vehicle data reports marketplace, Laravel, Paykeeper, queued API pipeline*.
- [ ] **Собес:** GDPR/152-ФЗ basics — устно.
- [ ] `TIME_LOG` + финальный EN commit.

## Перед ДЗ

- [DOMAIN.md](../DOMAIN.md) — решения #5, #16
- Корневой [06](../../../homework/06-deploy-portfolio-readme.md) — структура README
- **VPS + деплой + HTTPS:** [урок 00](../homework/00-local-environment.md) — nginx, домен, Let's Encrypt уже должны быть

## Техника

### Политика ПДн (минимальные блоки)

1. Кто оператор (ИП/ООО — placeholder)
2. Какие данные: ФИО, email, телефон, VIN/ГРЗ (как идентификаторы ТС)
3. Цели: регистрация, исполнение договора (отчёт), поддержка
4. Основания: ст. 6 152-ФЗ — согласие + исполнение договора
5. Передача: провайдеры API (Tronk и др.) — перечислить классы
6. Срок хранения и удаление по запросу
7. Права: доступ, исправление, удаление — email поддержки

### README EN (скелет)

```markdown
## Vin Report Platform (portfolio / commercial MVP)

Blade + Tailwind storefront, Filament admin, Paykeeper payments,
queued multi-provider report pipeline (PDF).

**Disclaimer:** Do not submit real personal data or production VINs in demo environments.
```

### Анонимизация

Подтверждение в UI → `ProfilePolicy::delete` → `anonymize()` → logout.

**Критерий:** наставник читает `/privacy` и README без вопросов «а где согласие?».

## Следующий урок

[07 — корзина и checkout](07-cart-checkout.md) (фаза 2 — полный ТЗ).

После **06** уже можно показывать staging; фаза 2 закрывает корзину, гараж, поддержку, прод-интеграции. Собесы **07–14** в корневом `homework/` — параллельно.

---

## Справочник

> [MENTORSHIP.md](../../../MENTORSHIP.md). [100 вопросов](../../../interview/100-questions-middle.md) — compliance на собесе.
