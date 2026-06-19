# Урок 11 — Хранение отчётов: local и S3

**Цель:** абстракция дисков Laravel; **prod** — PDF в S3, скачивание через **temporary signed URL**.

## Задание (сдать наставнику)

- [ ] **Шаг 1:** поля `reports.file_disk`, `reports.file_path`; миграция при необходимости.
- [ ] **Шаг 2:** `config/filesystems.php` — диск `reports` (local + s3 из `.env`).
- [ ] **Шаг 3:** `GenerateReportsJob` пишет через `Storage::disk(config('filesystems.reports'))`.
- [ ] **Шаг 4:** `ReportDownloadController` — Policy + `temporaryUrl` (15 мин) для s3; `download()` для local.
- [ ] **Шаг 5:** `.env.example` — `REPORTS_DISK=local`; документация для prod: `s3`, ключи AWS/YC.
- [ ] **Шаг 6:** тест с `Storage::fake('s3')`.
- [ ] **Собес:** зачем S3 при нескольких воркерах — устно.
- [ ] `TIME_LOG`.

## Перед ДЗ

- [Filesystem](https://laravel.com/docs/filesystem)
- Урок **10**

## Техника

### .env

```env
REPORTS_DISK=local   # dev
# prod:
REPORTS_DISK=s3
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=
AWS_BUCKET=vin-reports-prod
```

### Скачивание

```php
if ($report->file_disk === 's3') {
    return redirect(Storage::disk('s3')->temporaryUrl($report->file_path, now()->addMinutes(15)));
}
return Storage::disk('local')->download($report->file_path);
```

### Бэкап (документировать в README)

- БД — ежедневный dump
- S3 — versioning бакета; lifecycle для старых PDF по политике retention (152-ФЗ)

**Критерий:** переключение `REPORTS_DISK` меняет поведение без правок бизнес-логики.

## Следующий урок

[12 — Filament ops](12-filament-operations.md)

---

## Справочник

> [DOMAIN.md](../DOMAIN.md) — хранение PDF. [05 Docker](05-docker-compose.md) — volume vs S3.
