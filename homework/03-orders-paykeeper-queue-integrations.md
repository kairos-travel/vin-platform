# Урок 03 — «Купить сейчас»: заказ, Paykeeper, очередь, stub API

**Цель:** end-to-end VIN **учебно без корзины** (одна позиция сразу в `Order`): Paykeeper (test) → Job → PDF. В **продукте** основной путь — корзина ([урок 07](07-cart-checkout.md)); «Купить сейчас» потом = shortcut в корзину. Паттерны: [DOMAIN.md](../DOMAIN.md#паттерны-с-примерами).

## Задание (сдать наставнику)

- [ ] **Шаг 1:** форма заказа (VIN/GRZ/STS + выбор оффера) → `Order` + `OrderItem` (`pending_payment`).
- [ ] **Шаг 2:** редирект на Paykeeper (тестовый кабинет); webhook с **идемпотентностью** (`paykeeper_id` unique).
- [ ] **Шаг 3:** после `paid` — создать `Report`(ы) по `document_types` оффера; `dispatch(GenerateReportsJob)`.
- [ ] **Шаг 4:** `config/integrations.php` — цепочка из [Google Sheets](https://docs.google.com/spreadsheets/d/1XMaa4ime6GRpaHGWuVBZxS6OzGMP6Itk8Oz_puXxkXc/edit?gid=0#gid=0); feature flags `INTEGRATION_*_ENABLED`.
- [ ] **Шаг 5:** `App\Integrations\*` — минимум `TronkClient` stub + `ReportPipeline`; PDF на диск.
- [ ] **Шаг 6:** ЛК «Заказы» и «Отчёты» — списки; скачать PDF.
- [ ] **Демо:** тестовый платёж → `queue:work` → отчёт `completed`.
- [ ] **Собес:** §38 (очереди), §47 (идемпотентность) — устно.
- [ ] `TIME_LOG`.

## Перед ДЗ

**Термины (обязательно):** [GLOSSARY.md](../GLOSSARY.md) — Paykeeper, webhook, идемпотентность, Job, pipeline, feature flags, stub, ретраи, таймаут.

- [Queues](https://laravel.com/docs/queues)
- [DOMAIN.md](../DOMAIN.md) — примеры кода webhook
- Уроки **01–02** сданы

## Теория (кратко)

### Paykeeper в 5 шагов

1. Ты создаёшь `Order` (`pending_payment`).
2. Редирект пользователя на страницу оплаты Paykeeper.
3. Клиент платит картой.
4. Paykeeper **POST** на твой `/webhooks/paykeeper` (см. GLOSSARY).
5. Ты ставишь `paid` и **не генерируешь PDF в webhook** — только `GenerateReportsJob::dispatch()`.

### Идемпотентность

Один `paykeeper_id` → одна обработка. Повтор POST → `200 OK`, Job не дублируется.

### Job + pipeline

```text
Webhook (быстро, <1 сек)
    → Payment paid
    → dispatch GenerateReportsJob  ──→  queue:work (фон, 30–60 сек)
              → ReportPipeline
                    → stub/real Tronk
                    → stub/real ApiCloud
                    → PdfBuilder
                    → Report completed + file_path
```

Пайплайн читает **шаги** из `config/integrations.php` — меняешь порядок API без правки Job.

## Техника

### Шаг 1 — создание заказа

```php
// Псевдокод
$order = Order::create(['profile_id' => $profile->id, 'status' => 'pending_payment', ...]);
$order->items()->create([
    'service_offer_id' => $offer->id,
    'input_type' => 'vin',
    'input_value' => $validated['vin'],
    'price_snapshot' => $offer->price,
]);
```

### Шаг 2 — Paykeeper + идемпотентный webhook

См. полный пример в [DOMAIN.md § Идемпотентность](../DOMAIN.md#2-идемпотентность-webhook-paykeeper).

- Route `POST /webhooks/paykeeper` **без** CSRF (`VerifyCsrfToken` except)
- Проверка подписи Paykeeper
- Повторный webhook → `200 OK`, Job **не** дублируется

### Шаг 3 — Job и несколько отчётов

При оплате для каждого типа из `$offer->document_types`:

```php
foreach ($offer->document_types as $type) {
    $item->reports()->create(['type' => $type, 'status' => 'processing']);
}
GenerateReportsJob::dispatch($item);
```

Один оффер «Премиум» (`["vin","fines"]`) → **2 записи** `reports`.

### Шаг 4 — config/integrations.php

```php
'vin' => [
    'steps' => [
        ['provider' => 'tronk', 'method' => 'resolveIdentifiers'],
        ['provider' => 'api_cloud', 'method' => 'fines', 'fallback' => ['tronk', 'fines']],
    ],
],
'providers' => [
    'tronk' => ['enabled' => env('INTEGRATION_TRONK_ENABLED', false), ...],
],
```

На dev: `enabled=false` → pipeline пишет **fake data** в PDF.

### Шаг 5 — структура Integrations

```
app/Integrations/Tronk/TronkClient.php
app/Integrations/Pipeline/ReportPipeline.php
app/Jobs/GenerateReportsJob.php
```

**Частичный отчёт:** если шаг упал — секция в PDF «данные не получены», статус `completed` с `meta.partial=true` (не `failed` всего заказа).

### Шаг за шагом: Job → pipeline

1. **Webhook** создаёт записи `Report` со статусом `processing` (по `document_types` оффера).
2. **Job** получает `OrderItem`.
3. **ReportPipeline** загружает ключ пайплайна (например `vin_standard`) из конфига.
4. Для каждого **шага** конфига: если `INTEGRATION_*_ENABLED` — вызов клиента; иначе **stub** (фейковый JSON).
5. Результаты шагов складываются в массив `$data`.
6. **PdfBuilder** ренерит Blade → PDF → `Storage::put`.
7. `Report` → `completed`, `file_path` заполнен.

```php
// Упрощённо — ReportPipeline
public function run(OrderItem $item, Report $report): void
{
    $steps = config("integrations.pipelines.{$report->type}.steps");
    $data = [];
    foreach ($steps as $step) {
        $data = array_merge($data, $this->runStep($step, $item));
    }
    $path = $this->pdfBuilder->build($report, $data);
    $report->update(['status' => 'completed', 'file_path' => $path]);
}
```

### Шаг 6 — ЛК

- **Заказы:** дата, оффер, сумма, статус, ссылка на отчёты
- **Отчёты:** тип, статус, кнопка download

## Дальше по программе

- **П.7** корзина → [07](07-cart-checkout.md)
- **П.8** тарифы → [14](14-subscriptions-tariffs.md)
- **П.11** DaData, живые API → [10](10-integrations-live.md)

## Следующий урок

[04 — feature-тесты](04-feature-tests.md)

---

## Справочник

> Старый урок 03 (events) — та же идея «тяжёлое в Job». [08 SOLID](../../../homework/08-interview-solid.md) — клиенты провайдеров.
