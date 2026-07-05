<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $login = (string) $this->input('login');

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $this->merge(['email' => $login, 'phone' => null]);
        } else {
            $this->merge([
                'phone' => preg_replace('/[^\d+]/', '', $login),
                'email' => null,
            ]);
        }
    }

    public function withValidator(\Illuminate\Validation\Validator $validator): void
    {
        $validator->after(function ($validator) {
            $login = trim((string) $this->input('login'));
            $isEmail = (bool) filter_var($login, FILTER_VALIDATE_EMAIL);
            $isPhone = (bool) preg_match('/^\+?[0-9\s\-\(\)]{10,18}$/', $login);
            if (! $isEmail && ! $isPhone) {
                $validator->errors()->add('login', 'Введите корректный телефон или email');
            }
        });
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'login.required' => 'Введите ваш телефон или email',
            'login.max' => 'Максимальная длина телефона или email 255 символов',
            'password.required' => 'Введите ваш пароль',
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $field = $this->input('email') ? 'email' : 'phone';

        $credentials = [
            $field => $this->input($field),
            'password' => $this->input('password'),
        ];

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'login' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('login')).'|'.$this->ip());
    }
}
