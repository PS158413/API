<?php

namespace App\Http\Requests;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => 'required',
            'password' => 'required|min:6',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Custom validation message
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Please give your email',
            'password.required' => 'Please give your password',
        ];
    }
}
