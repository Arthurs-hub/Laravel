<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $productId = $this->route('product')->id;

        return [
            'sku' => [
                'required',
                'string',
                Rule::unique('products', 'sku')->ignore($productId),
            ],
            'name' => [
                'required',
                'string',
                Rule::unique('products', 'name')->ignore($productId),
            ],
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ];
    }
}
