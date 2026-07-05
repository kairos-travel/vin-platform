# Структура views и шаблонов

Ориентир, как организовать Blade-шаблоны в проекте, не ломая стиль Breeze.

## Что уже задал Breeze

```
views/
  layouts/      app.blade.php, guest.blade.php, navigation.blade.php
  components/   x-input, x-nav-link, x-primary-button … (Blade-компоненты)
  profile/partials/   delete-user-form, update-password-form …
  auth/ profile/ dashboard.blade.php welcome.blade.php
```

Breeze использует **компоненты** (`x-...`) и `layouts/`, а не классический `@include`.
Держимся того же стиля, чтобы не было двух разнородных подходов.

## Два подхода в Blade

| Подход | Чем подключают | Кто использует |
|--------|----------------|----------------|
| Классический | `@extends('layout')` + `@include('partials.header')` | старые проекты |
| Компонентный | `<x-app-layout>`, `<x-public.header />` | Laravel 9+, **Breeze (наш случай)** |

Вывод: используем **компонентный** подход.

## Рекомендованная структура

**1. Отдельный публичный layout** (ДЗ урок 02, шаг 2 — «не смешивать с Breeze auth layout»):

```
views/layouts/public.blade.php      ← шапка + футер + слот для контента
```

- `app.blade.php` — для ЛК (залогиненная зона)
- `guest.blade.php` — для auth-страниц (login/register)
- `public.blade.php` — для витрины (главная, страница услуги)

**2. Шапка/футер — компонентами, не `include/`:**

```
views/components/public/header.blade.php   → <x-public.header />
views/components/public/footer.blade.php   → <x-public.footer />
```

В одном стиле с существующими `x-nav-link`, `x-primary-button`.

**3. Страницы — по смыслу, не в `main/`:**

```
views/home.blade.php                 ← главная GET /
views/services/show.blade.php        ← страница услуги GET /services/{slug}
views/pages/tariffs.blade.php        ← заглушка /tariffs
```

## Замечания по именованию

- `views/main/index.blade.php` — рабочее, но `main` не общепринято. Для одной главной хватает `views/home.blade.php`. Папку заводим, когда страниц в разделе несколько (`views/pages/`, `views/services/`).
- `views/include/` для меню — не вводим. Вместо `@include` — компоненты (`components/public/header.blade.php`). Если всё же партиалы через `@include`, общепринятое имя папки — `partials/` (как у Breeze в `profile/partials/`), а не `include/`.

## Итог-ориентир

- Layout витрины → `layouts/public.blade.php`
- Шапка/футер → `components/public/*` (стиль Breeze)
- Главная → `home.blade.php`, услуга → `services/show.blade.php`
- `include/` не вводим; если партиалы — то `partials/`
