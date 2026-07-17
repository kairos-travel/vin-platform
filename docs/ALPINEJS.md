# Alpine.js — вопросы и ответы (vin-platform)

Справочник по **Alpine.js** в проекте: что это, зачем в Breeze/Filament, и ответы на вопросы из наставничества (включая прошлые в чате).

**Где подключён:** `resources/js/app.js` → `@vite` в layout.

```js
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();
```

**Где используется в нашем фронте:**

| Файл | Зачем |
|------|--------|
| `components/header-menu.blade.php` | бургер-меню |
| `components/auth/modal.blade.php` | auth popup |
| `components/auth-button.blade.php` | открыть popup |
| `components/home/faq.blade.php` | аккордеон FAQ |
| `components/home/features.blade.php` | кнопка «Зарегистрироваться» |
| `components/home/tariffs.blade.php` | кнопки «Оплатить» |

См. также: [BLADE_BASICS.md](./BLADE_BASICS.md) · [PAGES.md](./PAGES.md)

---

## Содержание

1. [Что такое Alpine.js](#1-что-такое-alpinejs)
2. [Alpine vs Blade vs Livewire vs Filament](#2-alpine-vs-blade-vs-livewire-vs-filament)
3. [Вопросы из проекта (с ответами)](#3-вопросы-из-проекта-с-ответами)
4. [Справочник директив в нашем коде](#4-справочник-директив-в-нашем-коде)
5. [Как читать цепочку auth popup](#5-как-читать-цепочку-auth-popup)
6. [Частые ошибки](#6-частые-ошибки)

---

## 1. Что такое Alpine.js

**Alpine.js** — маленькая JS-библиотека для **интерактива в HTML**: меню, модалки, табы, аккордеоны — **без React/Vue**.

Синтаксис — атрибуты в Blade:

```html
<div x-data="{ open: false }">
    <button @click="open = !open">Меню</button>
    <div x-show="open">...</div>
</div>
```

- **`x-data`** — локальное состояние компонента (как `data()` во Vue)
- **`@click`** — обработчик клика (сокращение для `x-on:click`)
- **`x-show`** — показать/скрыть элемент (CSS `display`)

Laravel **Breeze (Blade)** ставит Alpine из коробки — мы используем тот же стек.

---

## 2. Alpine vs Blade vs Livewire vs Filament

| Технология | Роль | Где у нас |
|------------|------|-----------|
| **Blade** | HTML на сервере, без JS | все `.blade.php` |
| **Alpine** | лёгкий JS в шаблоне | шапка, modal, FAQ |
| **Livewire** | компоненты с PHP-состоянием на сервере | Filament внутри |
| **Filament** | админка `/admin` | урок 02, шаг 4 |

**Auth popup на витрине** — Alpine (не уходим на `/login`).

**Страницы Breeze** `/login`, `/register` — обычный Blade + POST на сервер (без Alpine для отправки формы).

**Можно ли popup заменить только Breeze?** Да — тогда кнопка ведёт на `/login`. Popup — UX по Figma; формы позже можно подключить к тем же роутам.

---

## 3. Вопросы из проекта (с ответами)

### Q1. Что значит `x-data="{ open: false }"`?

**Когда спрашивали:** при вёрстке шапки (бургер-меню).

**Ответ:** создаёт **мини-компонент Alpine** на этом элементе с переменной `open`:

```blade
<header x-data="{ open: false }">
```

- `open: false` — меню **закрыто** при загрузке
- `@click="open = ! open"` — переключить true/false
- `x-show="open"` — панель видна только если `open === true`
- `:aria-expanded="open"` — для доступности (скринридеры)

**Аналогия:** переключатель «меню открыто / закрыто» живёт прямо в HTML, без отдельного `.js` файла.

**Файл:** `components/header-menu.blade.php`

---

### Q2. Что делает `@click="$dispatch('open-auth', 'login')"`?

**Когда спрашивали:** кнопка «Регистрация / Вход» в шапке.

**Ответ:** по клику Alpine **отправляет кастомное событие**:

| Часть | Значение |
|-------|----------|
| `@click` | по клику |
| `$dispatch('open-auth', 'login')` | событие `open-auth`, второй аргумент — payload `'login'` |

Событие **всплывает** до `window`. Модалка слушает:

```blade
@open-auth.window="openAuth($event.detail)"
```

В `openAuth`:
- `screen = 'login'` — экран входа
- `open = true` — показать overlay
- `overflow-hidden` на `body` — страница не скроллится

**Зачем не `<a href="/login">`?** По макету — **popup**, не переход. Кнопка и модалка **не связаны напрямую** — только через событие.

**Варианты payload:**

| Вызов | Экран модалки |
|-------|----------------|
| `$dispatch('open-auth', 'login')` | вход |
| `$dispatch('open-auth', 'register')` | регистрация |
| `$dispatch('open-auth', 'forgot')` | забыли пароль |

**Файлы:** `auth-button.blade.php` → `auth/modal.blade.php`

---

### Q3. Зачем Alpine в шапке, если есть Tailwind?

**Ответ:** Tailwind — **стили** (цвет, отступы). Alpine — **поведение** (открыть/закрыть меню, анимация). Они дополняют друг друга; Breeze так и устроен.

---

### Q4. Что такое `x-cloak`?

**Ответ:** скрывает элемент **до инициализации** Alpine. Иначе на долю секунды виден закрытый popup или mobile-menu.

```css
[x-cloak] { display: none !important; }
```

Пока Alpine не стартовал, блок с `x-cloak` не мелькает на экране.

**Файлы:** `header-menu.blade.php`, `auth/modal.blade.php`, `home/faq.blade.php`

---

### Q5. Что делает `@click.outside="open = false"`?

**Ответ:** клик **вне** элемента закрывает меню (стандарт для dropdown/mobile panel).

```blade
<div x-show="open" @click.outside="open = false">
```

**Файл:** `header-menu.blade.php`

---

### Q6. Что такое `:class="{ 'hidden': open }"`?

**Ответ:** **динамический** CSS-класс (двоеточие = bind):

- если `open === true` → добавится класс `hidden`
- используется для иконки бургер ↔ крестик

```blade
<path :class="{ 'hidden': open, 'inline-flex': ! open }" ... />
```

---

### Q7. Как работает FAQ-аккордеон?

**Ответ:** одна переменная `open` — номер **открытого** вопроса или `null`:

```blade
<div x-data="{ open: null }">
    <button @click="open = open === 0 ? null : 0">...</button>
    <div x-show="open === 0">ответ</div>
</div>
```

Только один ответ открыт; повторный клик — закрыть.

**Файл:** `components/home/faq.blade.php`

---

### Q8. Что делает `@submit.prevent` на форме в modal?

**Ответ:** `@submit.prevent` = `event.preventDefault()` — форма **не перезагружает** страницу.

Сейчас формы modal — **frontend mock** (`action="#"`). После подключения бекенда будет обычный POST на `/login` или fetch/Alpine + axios.

---

### Q9. `@keydown.escape.window` — что это?

**Ответ:** нажатие **Escape** в любом месте страницы закрывает модалку:

```blade
@keydown.escape.window="open && close()"
```

`.window` — слушатель на уровне окна, не только внутри div.

**Файл:** `auth/modal.blade.php`

---

### Q10. Можно ли обойтись без Alpine на главной?

**Ответ:** да, но хуже UX:

| Без Alpine | С Alpine |
|------------|----------|
| ссылка на `/login` | popup по Figma |
| FAQ — все ответы сразу или отдельные страницы | аккордеon |
| mobile menu — отдельная страница или CSS-only hack | бургер |

Для MVP по макету Alpine — нормальный выбор (как в Breeze).

---

## 4. Справочник директив в нашем коде

| Директива | Синоним | Пример в проекте |
|-----------|---------|------------------|
| `x-data` | — | `{ open: false }`, `{ open: null }` |
| `x-show` | — | mobile panel, modal, FAQ answer |
| `x-transition` | — | плавное появление modal |
| `x-cloak` | — | не мелькать до load |
| `@click` | `x-on:click` | бургер, dispatch |
| `@click.outside` | — | закрыть меню |
| `@keydown.escape.window` | — | закрыть modal |
| `@open-auth.window` | — | слушать событие auth |
| `:class` | `x-bind:class` | иконка бургера |
| `:aria-expanded` | `x-bind:aria-expanded` | a11y меню |
| `@submit.prevent` | — | формы modal |

---

## 5. Как читать цепочку auth popup

```
[Кнопка в шапке / hero / тарифах]
    @click="$dispatch('open-auth', 'login')"
              ↓  (событие всплывает)
[auth/modal.blade.php на window]
    @open-auth.window="openAuth($event.detail)"
              ↓
    open = true, screen = 'login'
              ↓
[x-show="open"] — overlay + dialog
[x-show="screen === 'login'"] — форма входа
```

Переключение экранов **внутри** modal — без новых событий:

```blade
<button @click="screen = 'register'">Регистрация</button>
<button @click="screen = 'forgot'">Забыли пароль?</button>
```

---

## 6. Частые ошибки

| Ошибка | Почему | Решение |
|--------|--------|---------|
| Modal не открывается | нет `<x-auth.modal />` в layout | проверить `layouts/main.blade.php` |
| `$dispatch` не работает | modal не на странице | modal только в `main` layout |
| Мелькает меню при load | нет `x-cloak` | добавить `x-cloak` + CSS |
| Alpine не работает вообще | не собран Vite | `npm run dev` или `npm run build` |
| `@click` не срабатывает | опечатка, конфликт JS | консоль браузера F12 |
| `open is not defined` | `x-show` вне блока с `x-data` | обернуть в общий `x-data` |

---

## Полезные ссылки

- [Alpine.js Docs](https://alpinejs.dev/)
- [Events — $dispatch](https://alpinejs.dev/essentials/events)
- [x-data](https://alpinejs.dev/directives/data)
- [Laravel Breeze](https://laravel.com/docs/starter-kits#laravel-breeze) — Alpine в Blade-стеке

---

## Добавлять сюда новые вопросы

Формат для новой записи:

```markdown
### QN. Текст вопроса?

**Когда спрашивали:** ...

**Ответ:** ...

**Файл:** `path/to/blade.php`
```
