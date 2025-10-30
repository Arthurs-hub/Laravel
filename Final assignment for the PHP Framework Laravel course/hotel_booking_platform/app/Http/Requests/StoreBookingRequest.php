<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'room_id' => 'required|exists:rooms,id',
            'started_at' => 'required|date|after_or_equal:today',
            'finished_at' => 'required|date|after:started_at',
            'adults' => 'required|integer|min:1|max:6',
            'children' => 'required|integer|min:0|max:4',
            'special_requests' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'started_at.after_or_equal' => 'Дата заезда не может быть раньше сегодняшнего дня.',
            'finished_at.after' => 'Дата выезда должна быть позже даты заезда.',
        ];
    }
}