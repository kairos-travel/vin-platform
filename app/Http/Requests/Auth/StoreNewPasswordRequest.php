<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreNewPasswordRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => [ 'required', 'string', 'max:255' ],
            'email' => [ 'nullable', 'string', 'max:255', 'email' ],
            'phone' => [ 'nullable', 'string', 'max:255' ],
            'password' => [ 'required', 'confirmed', Password::defaults() ],
            'token' => [ 'required', 'string', 'max:255' ],
        ];
    }

    public function messages(): array
    {
        return [
            'login.required' => 'Введите ваш телефон или email',
            'login.max' => 'Максимальная длина телефона или email 255 символов',
            'email.email' => 'Введите корректный email',
            'phone.max' => 'Максимальная длина телефона 11 символов',
            'password.required' => 'Придумайте другой пароль',
            'password.min' => 'Пароль должен быть не менее :min символов',
            'password.confirmed' => 'Пароли не совпадают',
            'token.required' => 'Токен не найден',
            'token.max' => 'Максимальная длина токена 255 символов',
        ];
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
}
