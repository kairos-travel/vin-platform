# Письмо для восстановления пароля: перевод и изменение вида

Инструкция как: (1) перевести текст письма на русский и (2) поменять его внешний вид.

Все `artisan`-команды запускать из папки проекта:

```bash
cd projects/vin-platform
```

Стек: Laravel 13.8, Breeze 2.4. Локаль уже `APP_LOCALE=ru`, почта ловится в Mailpit (`http://localhost:8026`).

**Staging / production (деплой + SMTP на сервере):** [PRODUCTION_MAIL.md](./PRODUCTION_MAIL.md)  
**REG.RU: ящик и `MAIL_*`:** [REG_RU_MAIL_SETUP.md](./REG_RU_MAIL_SETUP.md)

---

## Откуда вообще берётся это письмо

Когда вызывается `Password::sendResetLink()`, Laravel дёргает у модели `User` метод
`sendPasswordResetNotification()` (он приходит из трейта `Notifiable` +
`CanResetPassword`). По умолчанию он отправляет встроенный класс-уведомление
`Illuminate\Auth\Notifications\ResetPassword`.

Это уведомление строит письмо через **markdown-шаблон уведомлений**:

```
ResetPassword (текст письма)
        │
        ▼
resources/views/vendor/notifications/email.blade.php   ← общий каркас письма
        │
        ▼
resources/views/vendor/mail/html/*                     ← компоненты (кнопка, шапка, подвал)
        │
        ▼
resources/views/vendor/mail/html/themes/default.css    ← стили (цвета, отступы)
```

Пока ни один из этих файлов не опубликован — Laravel берёт их из `vendor/`, поэтому
письмо англоязычное и со стандартным дизайном.

---

## ЧАСТЬ 1. Перевести текст письма на русский

Текст стандартного письма (`You are receiving this email...`, кнопка `Reset Password`,
тема `Reset Password Notification`) переводится через **JSON-файл переводов**, где
ключ = английская строка, значение = перевод.

### Шаг 1.1. Создать файл `lang/ru.json`

Путь: `projects/vin-platform/lang/ru.json`

```json
{
    "Reset Password Notification": "Сброс пароля",
    "Hello!": "Здравствуйте!",
    "You are receiving this email because we received a password reset request for your account.": "Вы получили это письмо, потому что поступил запрос на сброс пароля для вашего аккаунта.",
    "Reset Password": "Сбросить пароль",
    "This password reset link will expire in :count minutes.": "Ссылка действительна :count минут.",
    "If you did not request a password reset, no further action is required.": "Если вы не запрашивали сброс пароля, никаких действий не требуется.",
    "Regards,": "С уважением,",
    "If you're having trouble clicking the \":actionText\" button, copy and paste the URL below into your web browser:": "Если кнопка «:actionText» не работает, скопируйте ссылку ниже и вставьте её в адресную строку браузера:"
}
```

> Важно: ключ слева должен **точно** совпадать с английской строкой из исходника
> (включая пунктуацию и плейсхолдеры вроде `:count`, `:actionText`). Иначе перевод не
> подхватится и останется английский текст.

### Шаг 1.2. Сбросить кэш и проверить

```bash
php artisan optimize:clear
```

Отправь ссылку сброса и открой письмо в Mailpit — текст должен стать русским.

> Файлы переводов кэшем конфига не кэшируются, но `optimize:clear` гарантированно сбросит всё лишнее.

---

## ЧАСТЬ 2. Изменить внешний вид письма

Есть три уровня — от самого простого к самому гибкому. Выбери под задачу.

### Вариант A — поменять стили/каркас всех писем (просто)

Публикуем шаблоны и тему, правим под себя. Затронет **все** markdown-письма проекта.

```bash
php artisan vendor:publish --tag=laravel-mail
php artisan vendor:publish --tag=laravel-notifications
```

Появятся файлы:

- `resources/views/vendor/mail/html/themes/default.css` — цвета, шрифты, отступы,
  вид кнопки. **Тут меняем дизайн** (например, цвет кнопки `.button-primary`,
  фон `body`, ширину `.inner-body`).
- `resources/views/vendor/mail/html/` — компоненты: `button.blade.php`,
  `header.blade.php`, `footer.blade.php`, `message.blade.php` и т.д.
  Можно вставить свой логотип в `header.blade.php`.
- `resources/views/vendor/notifications/email.blade.php` — общий каркас письма
  (порядок: приветствие → строки текста → кнопка → строки → подпись).

После правок снова открой письмо в Mailpit (кэшировать вьюхи не нужно для разработки).

### Вариант B — свой текст/кнопка/тема без отдельного класса (средне)

Если нужно переопределить **содержимое именно письма сброса** (тема, строки, кнопка,
текст приветствия), не трогая остальные письма — используем `ResetPassword::toMailUsing()`
в сервис-провайдере.

Файл: `projects/vin-platform/app/Providers/AppServiceProvider.php`, метод `boot()`:

```php
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

public function boot(): void
{
    ResetPassword::toMailUsing(function (object $notifiable, string $token) {
        $url = url(route('password.reset', [
            'token' => $token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Восстановление пароля — VIN Platform')
            ->greeting('Здравствуйте!')
            ->line('Вы запросили сброс пароля. Нажмите кнопку ниже, чтобы задать новый.')
            ->action('Сбросить пароль', $url)
            ->line('Ссылка действительна 60 минут.')
            ->line('Если вы не запрашивали сброс — просто проигнорируйте письмо.')
            ->salutation('С уважением, команда VIN Platform');
    });
}
```

> При этом варианте JSON-перевод для этих строк уже не нужен — ты пишешь текст прямо
> по-русски. Дизайн (тема/компоненты) всё равно берётся из Варианта A, если опубликован.

### Вариант C — полностью своё уведомление и свой Blade-шаблон (максимум контроля)

Когда нужен письмо с собственной вёрсткой (свой HTML, картинки, блоки) — делаем свой
класс уведомления и свой шаблон.

**Шаг C.1.** Создать уведомление:

```bash
php artisan make:notification ResetPasswordNotification
```

**Шаг C.2.** В `app/Notifications/ResetPasswordNotification.php` принять токен и собрать письмо
из своего markdown-шаблона:

```php
public function __construct(public string $token) {}

public function via(object $notifiable): array
{
    return ['mail'];
}

public function toMail(object $notifiable): MailMessage
{
    $url = url(route('password.reset', [
        'token' => $this->token,
        'email' => $notifiable->getEmailForPasswordReset(),
    ], false));

    return (new MailMessage)
        ->subject('Восстановление пароля — VIN Platform')
        ->markdown('emails.reset-password', ['url' => $url]);
}
```

**Шаг C.3.** Создать шаблон `resources/views/emails/reset-password.blade.php`:

```blade
<x-mail::message>
# Восстановление пароля

Вы запросили сброс пароля для аккаунта на VIN Platform.

<x-mail::button :url="$url">
Сбросить пароль
</x-mail::button>

Ссылка действительна 60 минут. Если вы не запрашивали сброс — проигнорируйте это письмо.

С уважением,<br>
Команда VIN Platform
</x-mail::message>
```

**Шаг C.4.** Сказать модели `User` использовать это уведомление. В
`app/Models/User.php` добавить метод:

```php
public function sendPasswordResetNotification($token): void
{
    $this->notify(new \App\Notifications\ResetPasswordNotification($token));
}
```

После этого `Password::sendResetLink()` отправит уже твоё письмо.

---

## Что выбрать

- Нужен только русский текст стандартного письма → **Часть 1** (`ru.json`). Достаточно.
- Плюс лёгкий рестайл (цвет кнопки, логотип) → **Часть 1 + Вариант A**.
- Хочется задать текст/тему именно письма сброса → **Вариант B** (без JSON).
- Нужна полностью своя вёрстка письма → **Вариант C**.

## Тестирование (любой вариант)

1. Mailpit запущен: `http://localhost:8026`.
2. Форма «Забыли пароль» → ввести email существующего пользователя.
3. Письмо появится в Mailpit — проверить текст, вид и ссылку.
4. Если что-то не подхватилось: `php artisan optimize:clear` и повторить.
