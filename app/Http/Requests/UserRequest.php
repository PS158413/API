<?php

namespace App\Http\Requests;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'last_name' => 'required|max:50',
            'email' => 'required|max:255',
            'password' => 'required|confirmed',
            'city' => 'required|max:50',
            'phone' => 'required|max:10',
            'birthday' => 'required|max:10',
        ];
    }

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
     * @return array
     * Custom validation message
     */
    public function messages()
    {
        return [
            'name.required' => 'Please give your name',
            'last_name.required' => 'Please give your last name',
            'name.max' => 'Please give your name between 100 characters',
            'email.required' => 'Please give your email',
            'password.required' => 'Please give your password',
            'city.required' => 'Please give your City name',
            'phone.required' => 'Please give your phone number',
            'phone.max' => 'Please give your phone number digits between 10 numbers',
            'birthday.required' => 'Please give your birthday',
        ];
    }
}
