<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            //request for receiptProduct
            'article_number' => 'max:50',
            'name' => 'max:50',
            'price' => 'max:50',

        ];
    }

    /**
     * @return array
     * Custom validation message
     */
    public function messages(): array
    {
        return [
            'article_number.max' => 'Article Number max 50',
            'name.max' => 'Name max 50',
            'price.max' => 'Price max 50',
        ];
    }
}
