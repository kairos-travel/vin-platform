# Глоссарий VIN-платформы

Термины из [DOMAIN.md](./DOMAIN.md) и уроков — простым языком. Перед уроками читай блок **«Термины»** в файле урока; полный список здесь.

---

## Оплата и заказы

### Paykeeper

**Что:** платёжный сервис (агрегатор). Пользователь вводит карту **на странице Paykeeper**, не на твоём сайте (или в iframe — по настройке).

**Как в проекте:**

```text
1. Laravel создаёт Order (pending_payment)
2. Редирект на Paykeeper с суммой и order_id
3. Клиент платит
4. Paykeeper шлёт POST на твой URL (webhook): «платёж id=123, orderid=100, sum=999»
5. Laravel помечает Payment paid → запускает генерацию отчётов
```

Документация: [paykeeper.ru](https://paykeeper.ru/). В dev — **тестовый кабинет** (урок 03).

### Webhook

**Что:** Paykeeper **сам** вызывает твой сервер HTTP-запросом («обратный вызов»), когда событие произошло (оплата, возврат). Ты не опрашиваешь их каждую секунду.

**Пример:** `POST https://твой-сайт.ru/webhooks/paykeeper` с телом `id=...&orderid=...&sum=...&sign=...`

### Webhooks на staging

**Что:** тот же webhook, но URL — **тестовый сервер** (staging), не прод.

```text
Paykeeper (тест) → https://staging.example.com/webhooks/paykeeper
```

Нужен публичный HTTPS (ngrok, staging VPS). Иначе Paykeeper не достучится с локалки без туннеля.

### Идемпотентность

**Что:** **повтор** одного и того же действия **не меняет результат** второй раз.

**Пример без идемпотентности:** webhook пришёл 3 раза → 3 раза запустился Job → 3 одинаковых отчёта.

**С идемпотентностью:** первый раз — `paid` + Job; второй и третий — «уже обработано», только `200 OK`.

### Идемпотентный webhook

**Как:** сохраняешь `paykeeper_id` платежа **unique** в БД. Повторный webhook с тем же `id` → не диспатчишь Job снова.

Подробный код: [DOMAIN.md § Идемпотентность](./DOMAIN.md#2-идемпотентность-webhook-paykeeper), урок [03](./homework/03-orders-paykeeper-queue-integrations.md).

### Ретраи (retry)

**Что:** Paykeeper (или очередь Laravel) **повторяет** запрос, если твой сервер не ответил `200` или упал по таймауту.

Отсюда и нужна идемпотентность — один платёж может прийти несколькими POST.

### Таймаут (timeout)

**Что:** максимальное время ожидания ответа.

- **HTTP к Tronk:** если API не ответил за 30 сек — обрываем, логируем, partial PDF или retry Job.
- **Webhook:** Paykeeper ждёт ответ от тебя ~несколько секунд — webhook-обработчик должен быть **быстрым** (только записать `paid` и поставить Job в очередь, без генерации PDF в том же запросе).

---

## Laravel и инфраструктура

### Breeze

**Что:** официальный **стартовый kit** Laravel: регистрация, вход, сброс пароля, профиль. Стек **Blade + Tailwind** — совпадает с твоим выбором.

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
```

Урок [02](./homework/02-breeze-catalog-filament.md). Это не отдельный фреймворк — пакет поверх Laravel.

### Filament

**Что:** готовая **админ-панель** для Laravel (CRUD в браузере без вёрстки с нуля). Оператор правит услуги, офферы, смотрит заказы.

```bash
composer require filament/filament
php artisan filament:install --panels
```

Урок [02](./homework/02-breeze-catalog-filament.md) — каталог; [12](./homework/12-filament-operations.md) — заказы и retry.

### Job (задача в очереди)

**Что:** класс вроде `GenerateReportsJob` — тяжёлую работу выполняет **воркер** в фоне (`php artisan queue:work`), не пользователь в браузере.

**Зачем:** сбор отчёта 30–60 сек — нельзя держать HTTP-запрос.

### Пайплайн (pipeline) по конфигу

**Что:** цепочка шагов «вызови API A → потом B → собери PDF». Порядок и fallback — в `config/integrations.php`, не захардкожен в Job.

```text
GenerateReportsJob
  └─ ReportPipeline::run()
       ├─ шаг 1: Tronk — VIN → ГРЗ, СТС
       ├─ шаг 2: API CLOUD — штрафы (если ошибка → Tronk)
       └─ PdfBuilder → файл на диск
```

Разбор по шагам: урок [03](./homework/03-orders-paykeeper-queue-integrations.md#шаг-за-шагом-job--pipeline), [10](./homework/10-integrations-live.md).

### Feature flags (флаги функций)

**Что:** переключатели в `.env` / конфиге — **включить или выключить** часть системы без удаления кода.

```env
INTEGRATION_TRONK_ENABLED=true
INTEGRATION_API_CLOUD_ENABLED=false
```

**«Включаешь провайдера, когда готов ключ и клиент»:** сначала пишешь `TronkClient` и тестируешь с `false` (stub); получил API-ключ → `true` на staging.

### Fake / stub провайдер

| Термин | Смысл |
|--------|--------|
| **Fake** | В тестах: `Http::fake()` — Laravel подменяет HTTP, реальный API не вызывается |
| **Stub** | В dev: класс `TronkClient` возвращает **захардкоженный** JSON вместо реального запроса |

Оба дают зелёный E2E **без денег** на внешние API. Потом stub меняется на реальный HTTP (урок 10).

### Shortcut (ярлык в UI)

**Не технический термин Laravel.** В нашем проекте: кнопка **«Купить сейчас»** = то же, что «В корзину» + сразу открыть `/cart`, **без** отдельной таблицы и логики. Один код — `addToCart()` + redirect.

### Nightly sync в object storage

**Что:** **ночной** (раз в сутки) скрипт копирует файлы с диска VPS в облачное хранилище (S3 / Yandex Object Storage) — **бэкап**.

```text
cron 03:00 → rsync / restic → бакет backup-vin-reports
```

Нужно, если PDF на **своём сервере** (local), а не сразу в S3. Если файлы уже в S3 — отдельный sync PDF не обязателен (бэкап БД всё равно нужен).

### S3 / object storage

Облачное хранилище файлов. Подробнее — урок [11](./homework/11-storage-s3.md), обсуждение local vs S3 в [QUESTIONS.md](./QUESTIONS.md).

---

## Домен (корзина, офферы)

### Offer vs Service

- **Service** — карточка услуги на главной.
- **Offer** — пакет с **ценой**; в `OrderItem` всегда `service_offer_id`.

### Корзина 1:1 с профилем

У зарегистрированного пользователя: **одна** корзина на **один** `Profile` (`carts.profile_id` unique). Не одна на `User`, если позже несколько профилей — корзина у активного профиля.

Гостевой checkout **не используем** (решение п.2) — корзина только у auth.

---

## Связь с уроками

| Термин | Урок |
|--------|------|
| Breeze | 02 |
| Filament | 02, 12 |
| Paykeeper, webhook, идемпотентность | 03 |
| Job, pipeline, stub, feature flags | 03, 10 |
| Корзина | 07 |
| Тарифы (дни + количество) | 14 |
| S3, nightly backup | 11, 15 |
