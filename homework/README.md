# Уроки VIN-платформы (00–15)

**Код:** `projects/vin-platform/` — все `php artisan` из этой папки.

**Формат:** Цель → Задание → Техника (шаги) → в конце «Справочник».

**Собесы** (нумерация **отдельная**): [homework/07–14](../../../homework/README.md#трек-b--собесы-07-14) в корне — параллельно с любого урока кода.

[DOMAIN.md](../DOMAIN.md) · [QUESTIONS.md](../QUESTIONS.md) · **[GLOSSARY.md](../GLOSSARY.md)** ← термины (Paykeeper, Breeze, Job…)

## Фаза 1 — ядро (E2E на staging)

| № | Файл | Тема |
|---|------|------|
| 00 | [00-local-environment.md](00-local-environment.md) | **Staging на VPS:** REG.RU, DNS, nginx, Laravel, HTTPS |
| 01 | [01-domain-migrations-policies.md](01-domain-migrations-policies.md) | **ERD** → миграции, модели, Policy |
| 02 | [02-breeze-catalog-filament.md](02-breeze-catalog-filament.md) | Breeze, главная, Filament-каталог |
| 03 | [03-orders-paykeeper-queue-integrations.md](03-orders-paykeeper-queue-integrations.md) | «Купить сейчас», Paykeeper, Job, stub API |
| 04 | [04-feature-tests.md](04-feature-tests.md) | Тесты заказа и webhook |
| 05 | [05-docker-compose.md](05-docker-compose.md) | Docker, queue worker |
| 06 | [06-deploy-readme-privacy.md](06-deploy-readme-privacy.md) | Staging, README, 152-ФЗ |

**Контрольная точка:** VIN end-to-end, профиль + заказы + отчёты.

## Фаза 2 — полный ТЗ

| № | Файл | Тема |
|---|------|------|
| 07 | [07-cart-checkout.md](07-cart-checkout.md) | Корзина, checkout, несколько позиций |
| 08 | [08-garage.md](08-garage.md) | Гараж, VIN из сохранённых ТС |
| 09 | [09-support-tickets.md](09-support-tickets.md) | Служба поддержки |
| 10 | [10-integrations-live.md](10-integrations-live.md) | Tronk, API CLOUD, SpectrumData, partial PDF |
| 11 | [11-storage-s3.md](11-storage-s3.md) | S3, signed URLs |
| 12 | [12-filament-operations.md](12-filament-operations.md) | Админка: заказы, retry, логи |
| 13 | [13-api-sanctum.md](13-api-sanctum.md) | API Sanctum *(опционально, после MVP)* |
| 14 | [14-subscriptions-tariffs.md](14-subscriptions-tariffs.md) | Тарифы 7/30 дней |
| 15 | [15-production-guest-refunds.md](15-production-guest-refunds.md) | Гость*, возвраты, мониторинг |

## Прогресс

### Фаза 1
- [ ] 00 · [ ] 01 · [ ] 02 · [ ] 03 · [ ] 04 · [ ] 05 · [ ] 06

### Фаза 2
- [ ] 07 · [ ] 08 · [ ] 09 · [ ] 10 · [ ] 11 · [ ] 12 · [ ] 13 · [ ] 14 · [ ] 15

## Архив

Сценарий «офлайн-события» — [homework/00–06](../../../homework/README.md) в корне (не удаляем).
