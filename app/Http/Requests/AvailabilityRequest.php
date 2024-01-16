<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AvailabilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'absence' => 'required',
            'start_time' => 'required',
            'finish_time' => 'required',
        ];
    }

    /**
     * @return array
     * Custom validation message
     */
    public function messages(): array
    {
        return [
            'absence.required' => 'Give a reason of absence',
            'start_time.required' => 'Give a Start time',
            'finish_time.required' => 'Give a Finish time',
        ];
    }
}
