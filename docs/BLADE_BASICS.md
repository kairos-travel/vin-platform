# Blade: шпаргалка по layout, include, компонентам, slot, @vite

Частые вопросы по шаблонам. Примеры — из Breeze и `layouts/app.blade.php` проекта.

**Alpine.js (бургер, modal, FAQ):** [ALPINEJS.md](./ALPINEJS.md)

---

## Как связаны `<x-app-layout>` и `layouts/app.blade.php`

```blade
{{-- dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">...</x-slot>
    <div>контент</div>
</x-app-layout>
```

```php
// app/View/Components/AppLayout.php
public function render(): View
{
    return view('layouts.app');  // ← рендерит этот файл
}
```

`<x-app-layout>` — это **компонент-обёртка**. Он просто подключает `layouts/app.blade.php`.
Всё, что внутри `<x-app-layout>...</x-app-layout>`, попадает в layout как `$slot` (и именованные слоты).

---

## 1. `@include` vs компонент (`<x-...>`)

### `@include` — вставить кусок HTML как есть

```blade
@include('layouts.navigation')
```

- Берёт файл `resources/views/layouts/navigation.blade.php` и **вставляет его текст** в это место.
- Это «копипаст через шаблон»: без своего PHP-класса, без атрибутов.
- Можно передать переменные: `@include('partials.card', ['title' => 'Заголовок'])`.
- Используют для **статичных кусков**: навигация, футер, один и тот же блок на многих страницах.

**Когда уместен:** простой partial без логики, старый стиль, быстрая вставка.

### Компонент — `<x-имя>` или `<x-папка.имя>`

```blade
<x-primary-button>Отправить</x-primary-button>
<x-input-label for="login" :value="__('Email')" />
<x-app-layout>...</x-app-layout>
```

- Файл: `resources/views/components/primary-button.blade.php`
- Или класс: `app/View/Components/AppLayout.php` → указывает на view.
- Можно передавать **атрибуты** (`class`, `type`, `:value="..."`).
- Внутри компонента: `$attributes`, `$slot`, свои переменные.
- Можно писать логику в PHP-классе компонента.

**Когда уместен:** переиспользуемые UI-элементы (кнопка, инпут), layout'ы, блоки с параметрами.

### Сравнение

| | `@include` | Компонент `<x-...>` |
|---|---|---|
| Синтаксис | `@include('path')` | `<x-name attr="...">` |
| Атрибуты / props | только через массив 2-м аргументом | `class`, `:value`, и т.д. |
| PHP-класс | не нужен | опционально (`AppLayout`) |
| Слоты | нет | есть (`$slot`, `<x-slot name="header">`) |
| Breeze | редко | **основной способ** |

### Что выбрать в нашем проекте

- **Breeze-зона (ЛК, auth):** компоненты (`<x-text-input>`, `<x-app-layout>`).
- **Публичный сайт (шаг 2 ДЗ):** тоже компоненты (`<x-public.header />`), не `views/include/`.
- `@include` в `app.blade.php` для `navigation` — наследие Breeze; для нового кода лучше компонент.

---

## 2. `@isset($header)` — что это значит

В `layouts/app.blade.php`:

```blade
@isset($header)
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            {{ $header }}
        </div>
    </header>
@endisset
```

**`@isset($header)`** — «если переменная `$header` **существует и не null**».

- Блок `<header>...</header>` рисуется **только** когда страница передала `$header`.
- Если не передала — блок не выводится (нет пустой полоски).

Откуда берётся `$header` — из **именованного слота** в `dashboard.blade.php`:

```blade
<x-app-layout>
    <x-slot name="header">
        <h2>Dashboard</h2>
    </x-slot>

    <div>основной контент</div>
</x-app-layout>
```

`<x-slot name="header">` → в layout попадает как переменная `$header`.

### Похожие директивы

| Директива | Условие |
|---|---|
| `@isset($var)` | переменная есть и не `null` |
| `@empty($var)` | нет или пустая (`""`, `[]`, `null`) |
| `@if(isset($var))` | то же, что `@isset`, но внутри `@if` |

---

## 3. `{{ $slot }}` — что это

**`$slot`** — место, куда попадает **всё содержимое между открывающим и закрывающим тегом компонента**, если это не именованный слот.

```blade
{{-- Страница --}}
<x-app-layout>
    <x-slot name="header">Заголовок</x-slot>

    <div class="py-12">        {{-- ← это попадёт в $slot --}}
        Контент дашборда
    </div>
</x-app-layout>
```

```blade
{{-- layouts/app.blade.php --}}
<main>
    {{ $slot }}   {{-- здесь окажется <div class="py-12">... --}}
</main>
```

### Именованные слоты vs default slot

| В странице | В layout / компоненте |
|---|---|
| `<x-slot name="header">` | `$header` или `{{ $header }}` |
| всё остальное внутри `<x-app-layout>` | `$slot` |

То же в `guest.blade.php` для login/register:

```blade
<x-guest-layout>
    <form>...</form>   {{-- форма → $slot в guest layout --}}
</x-guest-layout>
```

### В маленьком компоненте

`components/primary-button.blade.php`:

```blade
<button {{ $attributes->merge([...]) }}>
    {{ $slot }}   {{-- текст кнопки: <x-primary-button>Отправить</x-primary-button> --}}
</button>
```

---

## 4. `@vite(['resources/css/app.css', 'resources/js/app.js'])`

### Что делает

Подключает CSS и JS через **Vite** — сборщик фронта (вместо старых `<link href="mix/app.css">`).

В **dev** (`npm run dev`):
- в HTML вставляются теги на Vite dev-server (обычно `localhost:5173`);
- CSS/JS обновляются без пересборки (hot reload).

В **production** (`npm run build`):
- Vite собирает файлы в `public/build/`;
- `@vite` вставляет ссылки на собранные `app-XXXX.css` и `app-XXXX.js` из `public/build/manifest.json`.

### Связь с `vite.config.js`

```js
laravel({
    input: ['resources/css/app.css', 'resources/js/app.js'],
    refresh: true,
}),
```

Список в `@vite([...])` должен **совпадать** с `input` в конфиге — иначе ассеты не подтянутся.

### Что внутри этих файлов

- `resources/css/app.css` — Tailwind (`@tailwind base/components/utilities`).
- `resources/js/app.js` — JS (в Breeze часто axios/alpine).

### Когда не работает

| Проблема | Решение |
|---|---|
| Стили не грузятся | запустить `npm run dev` или `npm run build` |
| 404 на `localhost:5173` | Vite не запущен |
| На проде пусто | забыли `npm run build` перед деплоем |

### Зачем в layout

Пишут **один раз** в `<head>` layout'а — все страницы, наследующие layout, получают CSS/JS автоматически.

---

## Быстрая шпаргалка

```
Страница                    Layout (app.blade.php)
────────                    ──────────────────────
<x-app-layout>          →   layouts/app.blade.php
  <x-slot name="header"> →   $header  (@isset — показать шапку)
  основной контент      →   $slot    (в <main>)
</x-app-layout>

@include('layouts.navigation')  →  вставка navigation.blade.php (без слотов)

@vite([...])  →  CSS + JS через Vite

<x-text-input />  →  компонент с атрибутами и $slot
```

---

## Где читать в документации Laravel

- [Blade Templates](https://laravel.com/docs/blade)
- [Blade Components](https://laravel.com/docs/blade#components)
- [Blade Slots](https://laravel.com/docs/blade#slots)
- [Asset Bundling (Vite)](https://laravel.com/docs/vite)

См. также: [VIEWS_STRUCTURE.md](./VIEWS_STRUCTURE.md) — как организовать views в этом проекте.
