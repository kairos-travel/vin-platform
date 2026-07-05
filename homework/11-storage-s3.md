# Урок 11 — Хранение отчётов: local и S3

**Цель:** диски Laravel; prod — PDF в S3, **temporary signed URL**.

**Перед стартом:** урок **10**

---

## Шаг 1 — Поля `file_disk`, `file_path`

- [ ] Миграция при необходимости

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Migrations | [Migrations](https://laravel.com/docs/migrations) |

**Сделать:**

- `reports.file_disk`, `reports.file_path`

---

## Шаг 2 — Диск `reports` в config

- [ ] local + s3 из `.env`

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Filesystem | [Filesystem](https://laravel.com/docs/filesystem) |
| S3 driver | [Amazon S3 Compatible](https://laravel.com/docs/filesystem#amazon-s3-compatible-filesystems) |

**Сделать:**

- `config/filesystems.php` — диск `reports`

---

## Шаг 3 — Job пишет через Storage

- [ ] `Storage::disk(config('filesystems.reports'))`

**Прочитать:**

| Тема | Документация |
|------|----------------|
| File storage | [File Storage](https://laravel.com/docs/filesystem#file-uploads) |

**Сделать:**

- Обновить `GenerateReportsJob` / `PdfBuilder`

---

## Шаг 4 — ReportDownloadController

- [ ] Policy + `temporaryUrl` (s3) / `download()` (local)

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Temporary URLs | [Temporary URLs](https://laravel.com/docs/filesystem#temporary-urls) |
| Authorization | [Authorization](https://laravel.com/docs/authorization) |

---

## Шаг 5 — `.env.example` и документация

- [ ] `REPORTS_DISK=local`; prod — s3, ключи AWS/YC

**Сделать:**

- README: переключение диска без смены бизнес-логики

---

## Шаг 6 — Тест `Storage::fake('s3')`

**Прочитать:**

| Тема | Документация |
|------|----------------|
| Storage fake | [Storage Fake](https://laravel.com/docs/filesystem#testing) |

**Критерий:** `REPORTS_DISK` меняет поведение. Бэкап: БД + PDF (см. [GLOSSARY](../GLOSSARY.md)).

---

## Собес

- [ ] Зачем S3 при нескольких воркерах — устно

## TIME_LOG

- [ ] Записать часы

## Следующий урок

[12 — Filament ops](12-filament-operations.md)

---

## Справочник

> [DOMAIN.md](../DOMAIN.md) · [05 Docker](05-docker-compose.md)
