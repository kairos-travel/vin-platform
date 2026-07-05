# Пакет A (MVP) — чеклист по урокам

**Итого:** **187 ч** · **280 500 ₽** (1 500 ₽/ч) · срок ~**2–2,5 мес** (20 ч/нед)

Чеклист привязан к [урокам 00–07, 10](../homework/README.md). Часы — из [сметы A](./A-mvp.md), разбиты по шагам обучения.

**Легенда:** ✅ сделано · 🔄 в работе · ⬜ не начато

**Вне сметы A (сделано ранее, не в акт):** ERD, проектирование БД, VPS, DNS, nginx, Laravel на staging.

---

## Сводка прогресса

| Урок | Тема | Часы | ₽ | Статус |
|------|------|------|---|--------|
| 00 | HTTPS | 2 | 3 000 | ✅ |
| 01 | Миграции, модели, Policy | 18 | 27 000 | ✅ |
| 02 | Breeze, сайт VIN, Filament | 35 | 52 500 | 🔄 |
| 07 | Корзина, checkout | 25 | 37 500 | ⬜ |
| 03 | Paykeeper, webhook, Job (stub) | 24 | 36 000 | ⬜ |
| 05 | Очередь, queue worker | 16 | 24 000 | ⬜ |
| 10 | Live API VIN, pipeline | 45 | 67 500 | ⬜ |
| 04 | Feature-тесты | 8 | 12 000 | ⬜ |
| 06 | Privacy, README, сдача staging | 14 | 21 000 | ⬜ |
| | **Итого** | **187** | **280 500** | |

**Факт на сейчас (отчёт клиенту):** ~**26 ч / 39 000 ₽** — SSL 2 ч + урок 01 полностью 18 ч (миграции, модели, Policy, anonymize, tinker) + урок 02 шаг 1 «Breeze + регистрация + Profile» 6 ч *(ERD не входит)*. Шаг 1 урока 02 по факту тяжелее сметы (вход по телефону/email, Form Request, нормализация) — ближе к 8–9 ч; здесь учтены сметные 6 ч.

---

## Урок 00 — Staging, HTTPS · **2 ч**

*VPS, DNS, nginx, Laravel — готово ранее; в смету A входит только HTTPS.*

| | Задача | ч |
|---|--------|---|
| ✅ | DNS `@` и `www` → IP VPS | — |
| ✅ | nginx + PHP + MySQL + Laravel в `/var/www/vin-platform` | — |
| ✅ | **HTTPS** (Let's Encrypt / certbot) | 2 |
| ⬜ | `php artisan test` на staging | *(включено в урок 04)* |

**Урок:** [00-local-environment.md](../homework/00-local-environment.md)

---

## Урок 01 — Домен: миграции, модели, Policy · **18 ч**

*ERD — сделано ранее, часы не входят.*

| | Задача | ч | Статус |
|---|--------|---|--------|
| — | ERD фазы A (dbdiagram) | — | ✅ ранее |
| ✅ | Миграции: profiles, services, service_offers, orders, order_items, payments, reports | 8 | ✅ |
| ✅ | Миграции: carts, cart_items, integration_steps *(запас под урок 07/10)* | 2 | ✅ |
| ✅ | Модели + связи (Profile, Service, ServiceOffer…) | 3 | ✅ |
| ✅ | Модели: Order, OrderItem, Payment, Report, Cart… | 3 | ✅ |
| ✅ | `ProfilePolicy` (view/update/delete только свои) | 1 | ✅ |
| ✅ | `Profile::anonymize()` (черновик) | 1 | ✅ |
| ✅ | Проверка в tinker | 1 | ✅ |

**Урок:** [01-domain-migrations-policies.md](../homework/01-domain-migrations-policies.md)

---

## Урок 02 — Breeze, главная VIN, Filament · **35 ч**

*В пакете A на главной — **одна услуга VIN** (не 11 карточек; это пакет B).*

| | Задача | ч |
|---|--------|---|
| 🔄 | Laravel Breeze, Profile при регистрации *(+ вход по телефону/email — сверх сметы)* | 6 |
| ⬜ | Главная: одна услуга VIN по Figma (desktop) | 8 |
| ⬜ | Страница VIN: офферы, цены, «В корзину» (заглушка → урок 07) | 6 |
| ⬜ | **Адаптив** 9 страниц MVP (mobile + tablet) | 10 |
| ⬜ | Filament: CRUD Service, ServiceOffer, цепочки API | 4 |
| ⬜ | Сидер: VIN + 2–3 оффера | 1 |

**Урок:** [02-breeze-catalog-filament.md](../homework/02-breeze-catalog-filament.md)

---

## Урок 07 — Корзина и checkout · **25 ч**

*В пакете A — основной путь покупки («В корзину», не «Купить сейчас»).*

| | Задача | ч |
|---|--------|---|
| ⬜ | Модели Cart, CartItem *(миграции ✅)* | 2 |
| ⬜ | «В корзину», страница `/cart` | 6 |
| ⬜ | Checkout: Cart → Order + N OrderItem + `price_snapshot` | 8 |
| ⬜ | Один Payment на заказ, очистка корзины | 5 |
| ⬜ | Валидация: неактивные офферы, услуги без офферов | 2 |
| ⬜ | Тест: 2 позиции → 1 оплата → 2 набора Report | 2 |

**Урок:** [07-cart-checkout.md](../homework/07-cart-checkout.md)

---

## Урок 03 — Paykeeper, webhook, Job (stub) · **24 ч**

*Учебный путь одной позиции; в продукте логика уйдёт в корзину (07).*

| | Задача | ч |
|---|--------|---|
| ⬜ | Форма заказа → Order + OrderItem | 4 |
| ⬜ | Paykeeper (тест), редирект на оплату | 6 |
| ⬜ | Webhook + идемпотентность (`paykeeper_id`) | 6 |
| ⬜ | Job → ReportPipeline → PDF stub | 5 |
| ⬜ | ЛК: списки «Заказы» и «Отчёты», скачать PDF | 3 |

**Урок:** [03-orders-paykeeper-queue-integrations.md](../homework/03-orders-paykeeper-queue-integrations.md)

---

## Урок 05 — Очередь, queue worker · **16 ч**

| | Задача | ч |
|---|--------|---|
| ⬜ | `docker-compose` или systemd для `queue:work` | 8 |
| ⬜ | Настройка на staging VPS | 4 |
| ⬜ | Проверка: оплата → worker → отчёт `completed` | 4 |

**Урок:** [05-docker-compose.md](../homework/05-docker-compose.md)

---

## Урок 10 — Live API VIN, pipeline · **45 ч**

| | Задача | ч |
|---|--------|---|
| ⬜ | Tronk, API CLOUD, SpectrumData — реальные HTTP | 15 |
| ⬜ | Ветки VIN / GRZ / STS в pipeline | 8 |
| ⬜ | `service_offer_integration_steps` — цепочка на оффер | 8 |
| ⬜ | Filament: настройка цепочек | 4 |
| ⬜ | Частичный PDF, `reports.meta`, `integration_logs` | 6 |
| ⬜ | Retry / fallback по политике | 4 |

**Урок:** [10-integrations-live.md](../homework/10-integrations-live.md)

---

## Урок 04 — Feature-тесты · **8 ч**

| | Задача | ч |
|---|--------|---|
| ⬜ | Тест checkout (корзина → заказ) | 4 |
| ⬜ | Тест webhook Paykeeper (идемпотентность) | 4 |

**Урок:** [04-feature-tests.md](../homework/04-feature-tests.md)

---

## Урок 06 — Privacy, README, сдача · **14 ч**

| | Задача | ч |
|---|--------|---|
| ⬜ | `/privacy`, согласие ПДн при регистрации | 4 |
| ⬜ | UI удаления профиля → `anonymize()` | 3 |
| ⬜ | README, runbook для staging | 3 |
| ⬜ | Финальная проверка staging (`config:cache`, HTTPS) | 2 |
| ⬜ | Демо заказчику: корзина → оплата → live PDF | 2 |

**Урок:** [06-deploy-readme-privacy.md](../homework/06-deploy-readme-privacy.md)

---

## Оплата по этапам (для договора)

| Этап | Уроки | % | ₽ |
|------|-------|---|-----|
| 1. Ядро | 00, 01, 02, 07 | 30% | ~84 000 |
| 2. Оплата и API | 03, 05, 10 | 45% | ~126 000 |
| 3. Качество и сдача | 04, 06 | 25% | ~70 000 |

---

## Ссылки

- [A-mvp.md](./A-mvp.md) — технический scope
- [A-mvp-client.md](./A-mvp-client.md) — для заказчика
- [packages-overview-client.md](./packages-overview-client.md) — все пакеты кратко

*Обновлять статус и фактические часы в [TIME_LOG.md](../../../TIME_LOG.md).*
