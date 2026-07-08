@props(['resetToken' => null, 'resetEmail' => null])

@php
    $openReset = filled($resetToken) && filled($resetEmail);
@endphp

{{-- Figma auth popups. Login + register + forgot + reset — fetch + JSON --}}
<div
    x-data="authModal(@js([
        'openReset' => $openReset,
        'resetToken' => $resetToken,
        'resetEmail' => $resetEmail,
        'routes' => [
            'login' => route('login.store'),
            'register' => route('register.store'),
            'forgotPassword' => route('password.email'),
            'resetPassword' => route('password.store'),
        ],
    ]))"
    x-on:open-auth.window="openAuth($event.detail)"
    x-on:keydown.escape.window="open && close()"
>
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="auth-modal__overlay"
        x-cloak
        @click.self="close()"
    >
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="auth-modal__dialog"
            role="dialog"
            aria-modal="true"
            :aria-label="screen === 'login' ? '{{ __('Вход') }}' : (screen === 'register' ? '{{ __('Регистрация') }}' : (screen === 'reset' ? '{{ __('Новый пароль') }}' : '{{ __('Восстановление пароля') }}'))"
        >
            <button type="button" class="auth-modal__close" @click="close()" aria-label="{{ __('Закрыть') }}">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M6 6L18 18M18 6L6 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </button>

            {{-- Вход (269:516) --}}
            <div x-show="screen === 'login'">
                <h2 class="auth-modal__title">{{ __('Войдите в аккаунт') }}</h2>
                <p class="auth-modal__subtitle">
                    {{ __('Если у вас еще нет аккаунта, перейдите на вкладку Регистрация и зарегистрируйтесь в один клик') }}
                </p>
                <p x-show="statusMessage" x-text="statusMessage" class="auth-modal__subtitle text-brand-green" x-cloak></p>

                <div class="auth-modal__tabs">
                    <button type="button" class="auth-modal__tab" @click="screen = 'register'">{{ __('Регистрация') }}</button>
                    <button type="button" class="auth-modal__tab auth-modal__tab--active">{{ __('Вход') }}</button>
                </div>

                <form class="auth-modal__form" @submit.prevent="submitLogin">
                    @csrf
                    <div>
                        <label class="auth-modal__label" for="auth-login">{{ __('Телефон или электронная почта') }}</label>
                        <input
                            id="auth-login"
                            type="text"
                            name="login"
                            class="auth-modal__input"
                            required
                            autocomplete="username"
                        >
                        <p x-show="errors.login" x-text="errors.login" class="auth-modal__error" x-cloak></p>
                    </div>
                    <div>
                        <label class="auth-modal__label" for="auth-password">{{ __('Пароль') }}</label>
                        <input
                            id="auth-password"
                            type="password"
                            name="password"
                            class="auth-modal__input"
                            required
                            autocomplete="current-password"
                        >
                        <p x-show="errors.password" x-text="errors.password" class="auth-modal__error" x-cloak></p>
                    </div>
                    <button type="button" class="auth-modal__link" @click="screen = 'forgot'">{{ __('Забыли пароль?') }}</button>
                    <button type="submit" class="auth-modal__submit" :disabled="loading">
                        <span x-show="! loading">{{ __('Войти') }}</span>
                        <span x-show="loading" x-cloak>{{ __('Вход…') }}</span>
                    </button>
                </form>
            </div>

            {{-- Регистрация (269:536) --}}
            <div x-show="screen === 'register'">
                <h2 class="auth-modal__title">{{ __('Регистрация') }}</h2>
                <p class="auth-modal__subtitle">
                    {{ __('Введите ваш e-mail или номер телефона для регистрации и создайте надежный пароль') }}
                </p>

                <div class="auth-modal__tabs">
                    <button type="button" class="auth-modal__tab auth-modal__tab--active">{{ __('Регистрация') }}</button>
                    <button type="button" class="auth-modal__tab" @click="screen = 'login'">{{ __('Вход') }}</button>
                </div>

                <form class="auth-modal__form" @submit.prevent="submitRegister">
                    @csrf
                    <div>
                        <label class="auth-modal__label" for="auth-register-login">{{ __('Телефон или электронная почта') }}</label>
                        <div class="auth-modal__input-wrap">
                            <span class="auth-modal__prefix" x-show="isPhoneInput(registerLogin)" x-cloak>+7</span>
                            <input
                                name="login"
                                id="auth-register-login"
                                type="text"
                                class="auth-modal__input"
                                required
                                autocomplete="username"
                                x-model="registerLogin"
                            >
                        </div>
                        <p x-show="errors.login" x-text="errors.login" class="auth-modal__error" x-cloak></p>
                    </div>
                    <div>
                        <label class="auth-modal__label" for="auth-register-password">{{ __('Пароль') }}</label>
                        <input id="auth-register-password" name="password" type="password" class="auth-modal__input" required autocomplete="new-password">
                        <p x-show="errors.password" x-text="errors.password" class="auth-modal__error" x-cloak></p>
                    </div>
                    <div>
                        <label class="auth-modal__label" for="auth-register-password-confirm">{{ __('Подтвердите пароль') }}</label>
                        <input id="auth-register-password-confirm" name="password_confirmation" type="password" class="auth-modal__input" required autocomplete="new-password">
                        <p x-show="errors.password_confirmation" x-text="errors.password_confirmation" class="auth-modal__error" x-cloak></p>
                    </div>
                    <button type="submit" class="auth-modal__submit" :disabled="loading">
                        <span x-show="! loading">{{ __('Зарегистрироваться') }}</span>
                        <span x-show="loading" x-cloak>{{ __('Регистрация…') }}</span>
                    </button>
                </form>
            </div>

            {{-- Забыли пароль (283:525) --}}
            <div x-show="screen === 'forgot'">
                <h2 class="auth-modal__title">{{ __('Восстановление пароля') }}</h2>
                <p class="auth-modal__subtitle">
                    {{ __('Введите email или номер телефона — мы отправим инструкцию для сброса пароля') }}
                </p>

                <form class="auth-modal__form" @submit.prevent="submitForgotPassword">
                    @csrf
                    <div>
                        <label class="auth-modal__label" for="auth-forgot-login">{{ __('Телефон или электронная почта') }}</label>
                        <input
                            name="login"
                            id="auth-forgot-login"
                            type="text"
                            class="auth-modal__input"
                            required
                            autocomplete="username"
                            x-model="forgotLogin"
                        >
                        <p x-show="errors.login" x-text="errors.login" class="auth-modal__error" x-cloak></p>
                    </div>
                    <button type="submit" class="auth-modal__submit" :disabled="loading">
                        <span x-show="! loading">{{ __('Отправить') }}</span>
                        <span x-show="loading" x-cloak>{{ __('Отправка…') }}</span>
                    </button>
                    <button type="button" class="auth-modal__back" @click="screen = 'login'">{{ __('Вернуться ко входу') }}</button>
                </form>
            </div>

            {{-- Письмо отправлено (284:543) --}}
            <div x-show="screen === 'forgot-sent'" class="text-center">
                <div class="auth-modal__success-icon">
                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M5 13L9 17L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h2 class="auth-modal__title">{{ __('Письмо отправлено') }}</h2>
                <p class="auth-modal__subtitle">
                    {{ __('Проверьте почту и перейдите по ссылке из письма, чтобы задать новый пароль') }}
                </p>
                <button type="button" class="auth-modal__submit" @click="screen = 'login'">{{ __('Вернуться ко входу') }}</button>
            </div>

            {{-- Новый пароль (284:559 / 271:556) --}}
            <div x-show="screen === 'reset'">
                <h2 class="auth-modal__title">{{ __('Новый пароль') }}</h2>
                <p class="auth-modal__subtitle">
                    {{ __('Придумайте новый надёжный пароль для вашего аккаунта') }}
                </p>

                <form class="auth-modal__form" @submit.prevent="submitReset">
                    @csrf
                    <input type="hidden" name="token" :value="resetToken">
                    <input type="hidden" name="login" :value="resetEmail">
                    <p class="auth-modal__subtitle text-left" x-show="resetEmail" x-text="resetEmail" x-cloak></p>
                    <div>
                        <label class="auth-modal__label" for="auth-reset-password">{{ __('Пароль') }}</label>
                        <input id="auth-reset-password" name="password" type="password" class="auth-modal__input" required autocomplete="new-password">
                        <p x-show="errors.password" x-text="errors.password" class="auth-modal__error" x-cloak></p>
                    </div>
                    <div>
                        <label class="auth-modal__label" for="auth-reset-password-confirm">{{ __('Подтвердите пароль') }}</label>
                        <input id="auth-reset-password-confirm" name="password_confirmation" type="password" class="auth-modal__input" required autocomplete="new-password">
                        <p x-show="errors.password_confirmation" x-text="errors.password_confirmation" class="auth-modal__error" x-cloak></p>
                    </div>
                    <p x-show="errors.login" x-text="errors.login" class="auth-modal__error" x-cloak></p>
                    <button type="submit" class="auth-modal__submit" :disabled="loading">
                        <span x-show="! loading">{{ __('Сохранить пароль') }}</span>
                        <span x-show="loading" x-cloak>{{ __('Сохранение…') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
