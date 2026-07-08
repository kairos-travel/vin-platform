
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('headerAuth', (initial) => ({
    loggedIn: initial.loggedIn,
    userName: initial.userName,
    dashboardUrl: initial.dashboardUrl,

    init() {
        window.addEventListener('auth-success', (event) => {
            this.loggedIn = true;
            this.userName = event.detail.name;
            this.dashboardUrl = event.detail.dashboard_url;
        });
    },
}));

Alpine.data('authModal', (config) => ({
    open: config.openReset,
    screen: config.openReset ? 'reset' : 'login',
    loading: false,
    statusMessage: null,
    errors: { login: null, password: null, password_confirmation: null },
    registerLogin: '',
    forgotLogin: '',
    resetToken: config.resetToken,
    resetEmail: config.resetEmail,
    routes: config.routes,

    init() {
        if (this.open) {
            document.body.classList.add('overflow-hidden');
        }
    },

    clearResetUrl() {
        if (window.location.pathname.startsWith('/reset-password/')) {
            window.history.replaceState({}, document.title, '/');
        }
    },

    openAuth(detail) {
        this.screen = detail || 'login';
        this.errors = { login: null, password: null, password_confirmation: null };
        this.statusMessage = null;
        this.open = true;
        document.body.classList.add('overflow-hidden');
    },

    close() {
        if (this.screen === 'reset') {
            this.clearResetUrl();
        }

        this.open = false;
        document.body.classList.remove('overflow-hidden');
    },

    showTabs() {
        return ['login', 'register'].includes(this.screen);
    },

    isPhoneInput(value) {
        const v = (value ?? '').trim();
        if (! v) {
            return false;
        }
        if (v.includes('@')) {
            return false;
        }
        if (/[a-zA-Zа-яА-ЯёЁ]/.test(v)) {
            return false;
        }

        return /^[\d\s\-+()]+$/.test(v);
    },

    handleAuthSuccess(data) {
        this.close();

        window.dispatchEvent(new CustomEvent('auth-success', {
            detail: {
                name: data.name,
                dashboard_url: data.dashboard_url,
                csrf_token: data.csrf_token,
            },
        }));

        if (data.csrf_token) {
            document.querySelector('meta[name=csrf-token]')?.setAttribute('content', data.csrf_token);
            document.querySelectorAll('input[name=_token]').forEach((el) => {
                el.value = data.csrf_token;
            });
        }
    },

    async submitAuthForm(event, url, onSuccess) {
        this.errors = { login: null, password: null, password_confirmation: null };
        this.loading = true;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: new FormData(event.target),
                redirect: 'manual',
            });

            if (response.status === 422) {
                const data = await response.json().catch(() => ({}));
                this.errors.login = data.errors?.login?.[0] ?? null;
                this.errors.password = data.errors?.password?.[0] ?? null;
                this.errors.password_confirmation = data.errors?.password_confirmation?.[0] ?? null;

                return;
            }

            if (! response.ok) {
                const data = await response.json().catch(() => ({}));
                this.errors.login = data.message ?? 'Не удалось выполнить запрос. Попробуйте позже.';

                return;
            }

            if (! (response.headers.get('content-type') ?? '').includes('application/json')) {
                return;
            }

            const data = await response.json().catch(() => null);

            if (! data) {
                return;
            }

            onSuccess(data);
        } finally {
            this.loading = false;
        }
    },

    submitLogin(event) {
        return this.submitAuthForm(event, this.routes.login, (data) => this.handleAuthSuccess(data));
    },

    submitRegister(event) {
        return this.submitAuthForm(event, this.routes.register, (data) => this.handleAuthSuccess(data));
    },

    submitForgotPassword(event) {
        return this.submitAuthForm(event, this.routes.forgotPassword, () => {
            this.screen = 'forgot-sent';
        });
    },

    submitReset(event) {
        return this.submitAuthForm(event, this.routes.resetPassword, (data) => {
            this.resetToken = null;
            this.resetEmail = null;
            this.statusMessage = data.message ?? 'Пароль успешно изменён. Теперь вы можете войти.';
            this.screen = 'login';
            this.open = true;
            this.clearResetUrl();
        });
    },
}));

Alpine.start();
