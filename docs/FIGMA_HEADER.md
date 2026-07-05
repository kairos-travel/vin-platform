# Шапка 1:1 из Figma → Blade

Laravel **не импортирует** Figma автоматически. Pixel-perfect делается так: **экспорт ассетов + значения из Inspect → правка Blade/CSS**.

---

## Что нужно вытащить из Figma (один раз)

1. Открой макет: [Vin отчёт — Figma](https://www.figma.com/design/lH5KkodmwnpGfS3pv1SzFD/Vin-%D0%BE%D1%82%D1%87%D0%B5%D1%82?node-id=0-1)
2. Выдели **фрейм шапки** (header) на desktop-главной.
3. Справа **Inspect** (Dev Mode) — скопируй:

| Параметр | Куда в проекте |
|----------|----------------|
| Цвет красной кнопки | `tailwind.config.js` → `colors.brand.red` |
| Цвет текста меню | `colors.brand.menu` |
| Цвет «Company» (disabled) | `colors.brand.menu-muted` |
| Нижняя граница header | `colors.brand.header-border` |
| Font family + size меню | `tailwind.config.js` + классы в `header-menu-items` |
| Padding header (px, height) | `header-menu.blade.php` |
| Border-radius кнопки | `rounded-full` или точное значение из Inspect |

4. **Логотип:** выдели иконку → Export → **SVG** → сохрани как  
   `public/images/logo.svg`
5. **Шрифт:** если в макете не системный — подключи в `layouts/main.blade.php` (как Figtree в Breeze).

---

## Плагины Figma (опционально)

| Плагин | Зачем |
|--------|--------|
| **Inspect / Dev Mode** (встроено) | hex, padding, font — **основной способ** |
| **SVG Export** | логотип, иконки |
| Anima / Locofy | генерируют HTML/CSS — **только как черновик**, в Blade всё равно переносишь руками |

Автоматически «скачать и вставить в Laravel» **нельзя** — только экспорт + ручная правка (или правка сгенерённого CSS под Tailwind).

---

## Где лежит вёрстка шапки

```
resources/views/components/
  header-menu.blade.php       ← каркас (flex, mobile)
  header-menu-items.blade.php   ← пункты меню
  auth-button.blade.php         ← красная pill-кнопка
  site-logo.blade.php           ← логотип (SVG из public/images или inline)

tailwind.config.js            ← brand.* цвета из Figma
resources/css/app.css         ← @layer components .site-header ...
public/images/logo.svg        ← экспорт из Figma
```

---

## Как подставить точные цвета

1. Inspect в Figma → hex красной кнопки (например `#ED1C24`).
2. В `tailwind.config.js`:

```js
colors: {
    brand: {
        red: '#ED1C24',        // ← из Figma
        'red-hover': '#D41920',
        menu: '#737373',
        'menu-muted': '#D4D4D4',
        'header-border': '#EEEEEE',
    },
},
```

3. Перезапусти `npm run dev`.
4. Сравни `/` с макетом в Figma рядом (50% zoom).

---

## Проверка «один в один»

- [ ] Логотип — **тот же SVG**, не нарисованный от руку
- [ ] Красный кнопки = hex из Inspect
- [ ] Высота header = px из макета
- [ ] Меню по центру между логотипом и кнопкой
- [ ] Шрифт и `font-size` совпадают
- [ ] Mobile — отдельный фрейм в Figma (шаг адаптива позже)

---

## Если пришлёшь из Figma

Скинь в чат (или положи в `public/images/`):

1. `logo.svg`
2. Список hex: кнопка, текст меню, border
3. Font name + размеры (menu 14px, logo 18px и т.д.)

Тогда можно довести до pixel-perfect без угадывания.
