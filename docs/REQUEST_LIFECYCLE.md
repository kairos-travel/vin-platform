# Порядок выполнения: Request vs Form Request

Справочник по тому, **что и в каком порядке** отрабатывает в Laravel, когда запрос попадает в контроллер.

Примеры из проекта: `StoreRegisteredUserRequest`, `LoginUserRequest`, `ForgotPasswordRequest`, `StoreNewPasswordRequest`.

---

## Общая цепочка HTTP-запроса (до контроллера)

Это одинаково для `Request` и `FormRequest`:

```
1. Запрос приходит на сервер (GET/POST …)
2. public/index.php → bootstrap Laravel
3. HTTP Kernel: global middleware
      (TrustProxies, HandleCors, maintenance, TrimStrings, …)
4. Router: находит маршрут (routes/web.php, routes/auth.php)
5. Route middleware
      (например guest, auth, throttle)
6. Laravel создаёт экземпляр контроллера
7. Laravel резолвит аргументы метода контроллера через Service Container
      ↓
   Здесь расходятся Request и FormRequest (см. ниже)
8. Выполняется метод контроллера (store, create, …)
9. Response → middleware (в обратном порядке) → браузер
```

**Ключевой момент:** type-hint в сигнатуре метода (`Request $request` или `StoreRegisteredUserRequest $request`) — это **внедрение зависимости**. Контейнер создаёт объект **до** вызова метода контроллера.

---

## Обычный `Illuminate\Http\Request`

```php
public function store(Request $request): RedirectResponse
{
    // ...
}
```

### Что происходит

```
1. Router нашёл маршрут и middleware
2. Container создаёт/берёт текущий Request
      (тот же объект, что прошёл через middleware)
3. ❌ Автоматической валидации НЕТ
4. ❌ authorize() НЕ вызывается
5. Метод контроллера выполняется сразу
6. Валидация — только если ты сам вызвал, например:
      $request->validate([...]);
      Validator::make(...)->validate();
```

### Если вызываешь `$request->validate()` вручную

```
1. Берутся данные из $request->all()  (или переданный массив)
2. Собираются правила (массив в validate())
3. ValidationFactory создаёт Validator
4. Validator проверяет rules()
5. Если ошибки → ValidationException → redirect back с errors
6. Если ок → код ниже validate() продолжается
```

**Нет** `prepareForValidation()`, **нет** `withValidator()`, **нет** `authorize()` — только то, что ты написал сам.

### Что доступно в Request без Form Request

| Метод / свойство | Что даёт |
|---|---|
| `$request->all()` | все входные данные |
| `$request->input('login')` | одно поле |
| `$request->only('email', 'password')` | выбранные поля |
| `$request->validated()` | ❌ только после `validate()` |
| `$request->user()` | текущий пользователь (если auth middleware) |
| `$request->route('token')` | параметр из URL |

---

## `FormRequest` (например `StoreRegisteredUserRequest`)

```php
public function store(StoreRegisteredUserRequest $request): RedirectResponse
{
    // сюда попадаем только если валидация и authorize прошли
}
```

`FormRequest` extends `Request` и implements `ValidatesWhenResolved`.

Валидация запускается **автоматически** при резолве из контейнера — **до** входа в метод контроллера.

### Полный порядок (Form Request)

Источник: `ValidatesWhenResolvedTrait::validateResolved()` + `FormRequest::getValidatorInstance()`.

```
┌─ Резолв FormRequest из контейнера ─────────────────────────────┐
│                                                                │
│  1. prepareForValidation()                                     │
│       • нормализация данных ДО проверки                        │
│       • пример: login → email или phone через merge()          │
│                                                                │
│  2. authorize()                                                │
│       • return true  → идём дальше                             │
│       • return false → AuthorizationException (403)            │
│       • контроллер НЕ вызывается                               │
│                                                                │
│  3. getValidatorInstance() — сборка валидатора:                │
│                                                                │
│     3a. validationData()  →  $this->all()                      │
│         (уже с данными после prepareForValidation / merge)     │
│                                                                │
│     3b. rules()           →  массив правил                     │
│     3c. messages()        →  свои тексты ошибок                │
│     3d. attributes()    →  человекочитаемые имена полей        │
│                                                                │
│     3e. ValidationFactory->make(data, rules, messages, …)        │
│         → создан объект Validator (правила ещё НЕ прогнаны)      │
│                                                                │
│     3f. withValidator($validator)                              │
│         → регистрируешь доп. проверки                          │
│         → чаще всего $validator->after(function …)             │
│         → код inside after пока НЕ выполняется                 │
│                                                                │
│     3g. after() — если есть отдельный метод after() на классе  │
│         (альтернатива хукам внутри withValidator)              │
│                                                                │
│  4. $validator->fails()  — ЗДЕСЬ реально идёт проверка:        │
│       • правила из rules() (required, email, unique, …)        │
│       • затем callbacks из after() (в т.ч. из withValidator)   │
│                                                                │
│  5. Если fails() === true:                                     │
│       → failedValidation()                                     │
│       → ValidationException                                    │
│       → redirect back + errors                                 │
│       → контроллер НЕ вызывается                               │
│                                                                │
│  6. Если fails() === false:                                    │
│       → passedValidation()  (хук, обычно пустой)               │
│       → FormRequest считается «resolved»                       │
│                                                                │
└─ Метод контроллера store() наконец выполняется ────────────────┘
```

### Важно про `withValidator` и `after`

| Момент | Что происходит |
|---|---|
| Вызов `withValidator()` | Регистрация callback'ов (настройка валидатора) |
| Вызов `$validator->fails()` | Выполнение rules() + всех `after()` callback'ов |

То есть `withValidator` **не проверяет данные сам** — он **подписывает** дополнительную проверку, которая отработает на шаге 4.

### Пример из проекта (`StoreRegisteredUserRequest`)

```
POST /register
  login = "+79185551234"
  password = "secret"
  password_confirmation = "secret"

1. prepareForValidation()
     merge: phone = "+79185551234", email = null

2. authorize() → true

3. rules() проверят: login required, phone unique, password confirmed …

4. withValidator → after:
     если login не email и не phone → ошибка на поле login

5. Ошибок нет → RegisteredUserController@store()
```

---

## Сравнение в одной таблице

| | `Request` | `FormRequest` |
|---|---|---|
| Когда создаётся | до контроллера | до контроллера |
| Автовалидация | ❌ | ✅ |
| `authorize()` | ❌ | ✅, до rules |
| `prepareForValidation()` | ❌ | ✅, до rules |
| `rules()` / `messages()` | ❌ (если не FormRequest) | ✅ |
| `withValidator()` | ❌ | ✅ |
| `$request->validated()` | после ручного `validate()` | ✅ сразу в контроллере |
| Ошибка валидации | только если сам вызвал validate | автоматически, контроллер не вызывается |
| Контроллер при ошибке | зависит от твоего кода | не вызывается |

---

## `$request->validated()` в Form Request

После успешного прохождения валидации:

```php
$data = $request->validated();
```

Возвращает **только поля, перечисленные в `rules()`** (ключи правил).

Пример: если в rules есть `name`, `login`, `email`, `phone`, `password` — вернутся они (с учётом nullable и прошедших правил).

---

## Кастомная логика в Form Request (как `LoginUserRequest`)

Можно вынести в Form Request не только rules, но и **действие**:

```php
public function store(LoginUserRequest $request): RedirectResponse
{
    $request->authenticate();  // метод внутри LoginUserRequest
    // ...
}
```

Порядок:

```
1–6. Стандартная валидация Form Request (как выше)
7.   Метод контроллера вызван
8.   $request->authenticate() — твой код (Auth::attempt, rate limit, …)
```

`authenticate()` **не** вызывается автоматически — только когда ты сам вызвал в контроллере. Автоматически — только `authorize()` + валидация.

---

## Когда что использовать

| Ситуация | Что брать |
|---|---|
| Простой read-only input, без rules | `Request` |
| Одна строка validate в контроллере | `Request` + `$request->validate()` |
| Форма с rules, messages, authorize | `FormRequest` |
| Нормализация до rules (`login` → `email`) | `FormRequest` + `prepareForValidation()` |
| Сложная логика «либо email, либо phone» | `FormRequest` + `withValidator()` + `after` |
| Логин с attempt + throttle | `FormRequest` + метод `authenticate()` |

---

## Частые ошибки

1. **Ждать, что `prepareForValidation` проверяет данные** — нет, только подготавливает.
2. **Думать, что `withValidator` сразу валидирует** — нет, только регистрирует `after`.
3. **Вызывать `$request->validate()` в контроллере с FormRequest** — лишнее, валидация уже прошла.
4. **Поле в форме `login`, в rules только `email`** — без `prepareForValidation` email будет пустым.
5. **`authorize()` return false** — получишь 403, а не ошибки валидации на форме.

---

## Где смотреть в исходниках Laravel

| Что | Файл |
|---|---|
| Точка входа валидации Form Request | `Illuminate/Validation/ValidatesWhenResolvedTrait.php` → `validateResolved()` |
| Сборка валидатора | `Illuminate/Foundation/Http/FormRequest.php` → `getValidatorInstance()` |
| Правила по умолчанию | `FormRequest.php` → `createDefaultValidator()` |
