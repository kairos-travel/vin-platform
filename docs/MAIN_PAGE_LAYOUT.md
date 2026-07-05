# Главная страница: layout + view (как это работает)

Инструкция для шага 2 ДЗ. Без вёрстки — только связка route → controller → page → layout.

---

## Главное правило

**Layout не подключается в роуте.**

Роут знает только **страницу** (`main.index`). Layout страница подключает **сама** через `<x-main-layout>`.

---

## Цепочка (как у dashboard)

```
GET /
  ↓
routes/web.php  →  MainController@index
  ↓
return view('main.index')          ← имя СТРАНИЦЫ
  ↓
resources/views/main/index.blade.php   ← контент главной
  ↓
<x-main-layout>...</x-main-layout>     ← обёртка (компонент)
  ↓
app/View/Components/MainLayout.php   ← класс компонента
  ↓
return view('layouts.main')          ← общий каркас
  ↓
resources/views/layouts/main.blade.php ← html, head, {{ $slot }}
```

### Аналогия с Breeze

| Страница | Компонент | Класс | Layout |
|----------|-----------|-------|--------|
| `dashboard.blade.php` | `<x-app-layout>` | `AppLayout.php` | `layouts/app.blade.php` |
| `main/index.blade.php` | `<x-main-layout>` | `MainLayout.php` | `layouts/main.blade.php` |
| `auth/login.blade.php` | `<x-guest-layout>` | `GuestLayout.php` | `layouts/guest.blade.php` |

### Роли файлов

| Файл | Роль |
|------|------|
| `routes/web.php` | URL `/` → контроллер |
| `MainController` | `return view('main.index')` — **какую страницу** показать |
| `main/index.blade.php` | **Контент** главной (текст, блоки) |
| `layouts/main.blade.php` | **Общий каркас** (html, head, `@vite`, `{{ $slot }}`) |
| `MainLayout.php` | Связка `<x-main-layout>` → `layouts.main` |

---

## Команды: минимальная рабочая схема

Все команды — из папки проекта:

```bash
cd projects/vin-platform
```

### Шаг 1. Компонент MainLayout (если ещё нет)

```bash
php artisan make:component MainLayout
```

Появятся:
- `app/View/Components/MainLayout.php`
- `resources/views/components/main-layout.blade.php` ← **этот файл потом удалить** (см. шаг 3)

### Шаг 2. Папка и страница главной

```bash
mkdir -p resources/views/main
```

Создать файл `resources/views/main/index.blade.php`:

```blade
<x-main-layout>
    <h1>Главная</h1>
</x-main-layout>
```

**Если у тебя уже есть `resources/views/public/index.blade.php`** — переименовать папку:

```bash
mv resources/views/public resources/views/main
```

И в `main/index.blade.php` проверить, что внутри `<x-main-layout>...</x-main-layout>`.

> Имя view: `main.index` = папка `main/` + файл `index.blade.php`.

### Шаг 3. MainLayout → layouts/main (не components/)

В `app/View/Components/MainLayout.php` в методе `render()`:

```php
public function render(): View
{
    return view('layouts.main');
}
```

Удалить лишний blade (если artisan создал дефолтный):

```bash
rm resources/views/components/main-layout.blade.php
```

Каркас layout — только в `resources/views/layouts/main.blade.php` (с `{{ $slot }}`, `@vite`).

### Шаг 4. MainController

В `app/Http/Controllers/MainController.php`:

```php
public function index()
{
    return view('main.index');
}
```

(`view('main.index')` — если папка `main/`; не `public.index`.)

### Шаг 5. Роут (обычно уже есть)

В `routes/web.php`:

```php
Route::get('/', [MainController::class, 'index'])->name('main');
```

### Шаг 6. Проверка

```bash
php artisan serve
```

Открыть `http://127.0.0.1:8000` — должны быть html из `layouts/main.blade.php` и `<h1>Главная</h1>` внутри `<main>`.

---

## public vs main — что выбрать

| Путь | Смысл |
|------|--------|
| `views/main/index.blade.php` | главная страница, view `main.index` — **рекомендуется** (совпадает с `MainController`, route name `main`) |
| `views/public/index.blade.php` | view `public.index` — нужен другой `return view('public.index')` в контроллере |

Переименование `public/` → `main/` логично, если контроллер уже отдаёт `main.index`.

---

## Частые ошибки

| Ошибка | Причина |
|--------|---------|
| `Unable to locate component [main-layout]` | нет `MainLayout.php` или `render()` смотрит не туда |
| View `[main.index] not found` | нет файла `resources/views/main/index.blade.php` |
| Пустая страница / нет стилей | в `layouts/main.blade.php` нет `@vite([...])` или не запущен `npm run dev` |
| Два layout-файла | остался `components/main-layout.blade.php` — удалить, оставить только `layouts/main.blade.php` |

---

## Лишнее (можно убрать позже)

Если создавал `PublicLayout` и не используешь:

```bash
rm app/View/Components/PublicLayout.php
# и связанные views/components/public-layout.blade.php — если есть
```

Для главной достаточно **MainLayout** + **layouts/main**.

---

## Дальше (шаг 2 ДЗ)

- В `layouts/main.blade.php`: шапка, футер (или `<x-public.header />` и т.д.)
- Контент главной — только в `main/index.blade.php`
- Auth layout (`guest`) и ЛК (`app`) **не трогать**

См. также: [BLADE_BASICS.md](./BLADE_BASICS.md), [VIEWS_STRUCTURE.md](./VIEWS_STRUCTURE.md).
