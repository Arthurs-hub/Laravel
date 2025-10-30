<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
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
            'floor_area' => 'required|numeric|min:0',
            'type' => 'required|string|max:100',
            'price' => 'required|integer|min:0',
            'facilities' => 'array',
            'facilities.*' => 'exists:facilities,id'
        ];
    }
}