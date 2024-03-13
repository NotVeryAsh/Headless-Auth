<?php

namespace App\Http\Requests\Events;

use Illuminate\Foundation\Http\FormRequest;

class CreateEventsRequest extends FormRequest
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
                'max:255',
            ],
            'start' => [
                'required',
                'date',
                'before_or_equal:end',
            ],
            'end' => [
                'required',
                'date',
                'after_or_equal:start',
            ],
            'all_day' => [
                'sometimes',
                'accepted',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.string' => 'The title is invalid.',
            'title.max' => 'The title must not be more than 255 characters long.',
            'title.required' => 'The title is required.',
            'start.required' => 'The start date is required.',
            'start.date' => 'The start date is invalid.',
            'start.before_or_equal' => 'The start date must be the same as or before the end date.',
            'end.required' => 'The end date is required.',
            'end.date' => 'The end date is invalid.',
            'end.after_or_equal' => 'The end date must be the same as or after the start date.',
        ];
    }
}
