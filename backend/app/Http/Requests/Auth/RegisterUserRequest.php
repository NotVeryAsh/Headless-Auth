<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'password' => [
                'required',
                'min:8',
                'max:255',
                'confirmed',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Email is invalid.',
            'email.max' => 'Email must not be greater than 255 characters.',
            'email.unique' => 'Email has already been taken.',
            'name.required' => 'Name is required.',
            'name.string' => 'Name is invalid.',
            'name.max' => 'Name must not be greater than 255 characters.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password must not be greater than 255 characters.',
            'password.confirmed' => 'Passwords do not match.',
        ];
    }
}
