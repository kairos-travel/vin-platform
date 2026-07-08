<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreRegisteredUserRequest extends FormRequest
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
            'name' => [ 'nullable', 'string', 'max:255' ],
            'login' => [ 'required', 'string', 'max:255', 'unique:users,login' ],
            'email' => [ 'nullable', 'string', 'max:255', 'email', 'unique:users,email' ],
            'phone' => [ 'nullable', 'string', 'max:255', 'unique:users,phone' ],
            'password' => [ 'required', 'confirmed', Password::defaults() ],
        ];
    }

    public function messages(): array
    {
        return [
            'login.required' => 'Введите ваш телефон или email',
            'login.unique' => 'Этот телефон или email уже занят',
            'password.required' => 'Придумайте другой пароль',
            'password.min' => 'Пароль должен быть не менее :min символов',
            'password.confirmed' => 'Пароли не совпадают',
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
