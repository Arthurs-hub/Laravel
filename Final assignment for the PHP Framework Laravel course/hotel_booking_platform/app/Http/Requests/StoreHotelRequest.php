<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHotelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'poster_url' => 'nullable|url|max:100',
            'address' => 'required|string|max:500',
            'country' => 'nullable|string|max:100',
            'facilities' => 'array',
            'facilities.*' => 'exists:facilities,id'
        ];
    }
}