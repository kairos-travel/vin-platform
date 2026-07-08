# Карта страниц проекта

Реестр URL, названий и стека UI.  
**Статус:** ✅ готово · 🔄 частично · ⬜ запланировано

См. также: [BLADE_BASICS.md](./BLADE_BASICS.md) · [ALPINEJS.md](./ALPINEJS.md) · [FILAMENT_FRONTEND.md](./FILAMENT_FRONTEND.md)

---

## Легенда технологий

| Метка | Что это |
|-------|---------|
| **Blade + Tailwind** | Обычные Blade-шаблоны, `@vite`, классы в `app.css` |
| **Alpine** | Интерактив на клиенте (`x-data`, `@click`) поверх Blade |
| **Breeze** | Laravel Breeze: auth-страницы, ЛК, `layouts/app.blade.php` |
| **Filament** | Админ-панель `/admin`: Livewire + Filament Tables/Forms |
| **API JSON** | Sanctum, без HTML (урок 13) |
| **Webhook** | POST без UI |

---

## Публичный сайт (витрина)

Layout: `layouts/main.blade.php` · компонент `<x-main-layout>`

| URL | Название | View / компонент | Технологии | Статус | Урок |
|-----|----------|------------------|------------|--------|------|
| `/` | Главная | `main/index.blade.php` + `components/home/*` | Blade + Tailwind, Alpine (бургер в шапке, auth modal) | 🔄 | 02 |
| `/services/{slug}` | Страница услуги (VIN и др.) | `services/show.blade.php` (план) | Blade + Tailwind | ⬜ | 02 |
| `/tariffs` | Тарифы | `pages/tariffs.blade.php` (план) | Blade + Tailwind; сначала заглушка «Скоро», потом карточки пакетов | ⬜ | 02 / 14 |
| `/privacy` | Политика конфиденциальности | `pages/privacy.blade.php` (план) | Blade + Tailwind, статический текст RU | ⬜ | 06 |

### Секции главной (не отдельные URL)

| Блок | Компонент | Технологии |
|------|-----------|------------|
| Hero | `home/hero` | Blade + Tailwind |
| Сетка услуг | `home/services-grid` | Blade (пока статический массив → БД в шаге 3) |
| Партнёры | `home/partners` | Blade |
| Преимущества | `home/features` | Blade |
| Тарифы (блок) | `home/tariffs` | Blade |
| FAQ | `home/faq` | Blade, опц. Alpine для аккордеона |
| CTA | `home/cta` | Blade |
| Шапка | `header-menu`, `auth-button` | Blade + Tailwind + **Alpine** (меню, auth modal) |
| Футер | `site-footer` | Blade + Tailwind |

---

## Auth — полные страницы (Breeze)

Layout: `layouts/guest.blade.php`

| URL | Название | View | Технологии | Статус | Урок |
|-----|----------|------|------------|--------|------|
| `/login` | Вход | `auth/login.blade.php` | **Breeze** (Blade + Tailwind) | ✅ | 02 |
| `/register` | Регистрация | `auth/register.blade.php` | **Breeze** | ✅ | 02 |
| `/forgot-password` | Забыли пароль | `auth/forgot-password.blade.php` | **Breeze** | ✅ | 02 |
| `/reset-password/{token}` | Новый пароль | `auth/reset-password.blade.php` | **Breeze** | ✅ | 02 |
| `/verify-email` | Подтверждение email | `auth/verify-email.blade.php` | **Breeze** | ✅ | 02 |
| `/confirm-password` | Подтверждение пароля | `auth/confirm-password.blade.php` | **Breeze** | ✅ | 02 |

### Auth — модальные окна на витрине

| Где | Компонент | Технологии | Статус |
|-----|-----------|------------|--------|
| Любая страница с `main` layout | `components/auth/modal.blade.php` | **Alpine** + Blade; формы пока `action="#"` | 🔄 |

Экраны modal: login, register, forgot, forgot-sent, reset. Подключение к роутам Breeze — на бекенде.

---

## Личный кабинет (клиент)

Layout: `layouts/app.blade.php` (Breeze navigation)

| URL | Название | View | Технологии | Статус | Урок |
|-----|----------|------|------------|--------|------|
| `/dashboard` | Дашборд ЛК | `dashboard.blade.php` | **Breeze** | ✅ (заглушка) | 02 |
| `/profile` | Профиль | `profile/edit.blade.php` | **Breeze** | ✅ | 02 |
| `/account/orders` | Мои заказы | план: `account/orders/*` | Blade + Tailwind (можно Breeze layout) | ⬜ | 03+ |
| `/account/reports` | Мои отчёты | план | Blade + Tailwind | ⬜ | 03+ |
| `/account/garage` | Гараж (ТС) | план | Blade + Tailwind, опц. **Alpine** (модалка) | ⬜ | 08 |
| `/account/support` | Служба поддержки | план | Blade + Tailwind | ⬜ | 09 |
| `/account/support/{ticket}` | Переписка по тикету | план | Blade + Tailwind | ⬜ | 09 |

---

## Корзина и оплата

| URL | Название | View | Технологии | Статус | Урок |
|-----|----------|------|------------|--------|------|
| `/cart` | Корзина | план | Blade + Tailwind, формы POST | ⬜ | 07 |
| `/checkout` | Оформление (если отдельный шаг) | план | Blade + Tailwind | ⬜ | 07 |
| Paykeeper redirect | Оплата (внешний) | — | Редирект на Paykeeper | ⬜ | 03 |
| `POST /webhooks/paykeeper` | Webhook оплаты | Controller | **Webhook**, без UI | ⬜ | 03 |

Урок 03 (учебный путь «Купить сейчас»): форма заказа может быть на странице услуги без отдельного URL checkout.

---

## Админка

| URL | Название | Реализация | Технологии | Статус | Урок |
|-----|----------|------------|------------|--------|------|
| `/admin-preview` | Preview вёрстки админки | `admin/preview.blade.php` | Blade + Tailwind (`.admin-*`) | ✅ | 02 (front) |
| `/admin` | Дашборд | Filament Panel | **Filament** + Livewire | ⬜ | 02, 12 |
| `/admin/services` | Услуги CRUD | Filament Resource | **Filament** | ⬜ | 02 |
| `/admin/service-offers` | Офферы CRUD | Filament Resource | **Filament** | ⬜ | 02 |
| `/admin/orders` | Заказы (просмотр) | Filament Resource | **Filament** | ⬜ | 12 |
| `/admin/reports` | Отчёты | Filament Resource | **Filament** | ⬜ | 12 |
| `/admin/integration-logs` | Логи интеграций | Filament Resource | **Filament** | ⬜ | 12 |
| `/admin/support-tickets` | Тикеты поддержки | Filament Resource | **Filament** | ⬜ | 09 |
| `/admin/tariff-plans` | Тарифные планы | Filament Resource | **Filament** | ⬜ | 14 |

Тема: `resources/css/filament/admin/theme.css` — см. [FILAMENT_FRONTEND.md](FILAMENT_FRONTEND.md).

---

## API (Sanctum)

Префикс `/api/v1` · JSON, без Blade.

| Method + URL | Название | Технологии | Статус | Урок |
|--------------|----------|------------|--------|------|
| `GET /api/v1/services` | Каталог услуг | Laravel API + Sanctum | ⬜ | 13 |
| `GET /api/v1/services/{slug}/offers` | Офферы услуги | Laravel API | ⬜ | 13 |
| `POST /api/v1/orders` | Создание заказа | Laravel API | ⬜ | 13 |

---

## Служебные

| URL | Название | Технологии | Статус | Урок |
|-----|----------|------------|--------|------|
| `/up` | Health check | Laravel built-in | ⬜ | 15 |
| `/welcome` | Дефолт Laravel | Blade (можно удалить) | ✅ (не используется) | — |

---

## Сводка по стеку

```
Витрина (/, /services, /tariffs, /privacy)
  └── Blade + Tailwind + Alpine (шапка, auth modal, FAQ)

Auth страницы (/login, /register, …)
  └── Breeze (Blade + Tailwind)

ЛК (/dashboard, /profile, /account/*)
  └── Breeze layout + Blade (+ Alpine где нужна интерактивность)

Админка (/admin/*)
  └── Filament (Livewire + Alpine внутри) + theme.css

API (/api/v1/*)
  └── JSON, Sanctum

Preview админки (/admin-preview)
  └── Blade + Tailwind (временный макет)
```

---

## Файлы views (текущее дерево)

```
views/
  main/index.blade.php          ← главная
  admin/preview.blade.php       ← preview админки
  auth/*.blade.php              ← Breeze auth
  dashboard.blade.php           ← ЛК
  profile/                      ← Breeze profile
  components/
    home/                       ← секции главной
    auth/modal.blade.php        ← Alpine auth popup
    admin/sidebar.blade.php     ← preview sidebar
    header-menu*.blade.php
    site-footer.blade.php
  layouts/
    main.blade.php              ← публичный сайт
    app.blade.php               ← Breeze ЛК
    guest.blade.php             ← Breeze auth
  components/layouts/
    admin-preview.blade.php     ← layout preview админки
```

Планируемые: `services/show.blade.php`, `pages/tariffs.blade.php`, `pages/privacy.blade.php`, `account/*`.
