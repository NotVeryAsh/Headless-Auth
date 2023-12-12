<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::exists('users', 'email'),
            ],
            'password' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {

        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Email is incorrect.',
            'email.max' => 'Email is incorrect.',
            'email.exists' => 'Email is incorrect.',
            'password.required' => 'Password is required.',
            'password.string' => 'Password is incorrect. Try again or click "Forgot Password" to reset your password.',
            'password.max' => 'Password is incorrect. Try again or click "Forgot Password" to reset your password.',
        ];
    }
}
