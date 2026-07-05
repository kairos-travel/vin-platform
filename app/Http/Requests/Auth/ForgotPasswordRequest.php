<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
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
            'login' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'login.required' => 'Введите ваш телефон или email',
            'login.max' => 'Максимальная длина телефона или email 255 символов',
        ];
    }
}
