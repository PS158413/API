<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'article_number' => 'required',
            'name' => 'required|max:50',
            'description' => 'required|max:200',
            'image' => '',
            'color' => 'required',
            'height_cm' => 'required',
            'width_cm' => 'required',
            'depth_cm' => 'required',
            'weight_gr' => 'required',
            'barcode' => 'required',
            'stock' => 'required',
        ];
    }

    /**
     * @return array
     * Custom validation message
     */
    public function messages(): array
    {
        return [
            'article_number.required' => 'Number is required',
            'name.required' => 'Name is required',
            'description.required' => 'Description is required',
            'color.required' => 'Color is required',
            'height_cm.required' => 'Height is required',
            'width_cm.required' => 'Width is required',
            'depth_cm.required' => 'Depth is required',
            'weight_gr.required' => 'Weight is required',
            'barcode.required' => 'Barcode is required',
            'stock.required' => 'Stock is required',
        ];
    }
}
