<?php

namespace App\Http\Requests\Calendar;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCalendarRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255'
            ]
        ];
    }

    function messages(): array
    {
        return [
            'title.string' => 'The title is invalid.',
            'title.max' => 'The title must not be more than 255 characters long.',
            'title.required' => 'The title is required.'
        ];
    }
}
