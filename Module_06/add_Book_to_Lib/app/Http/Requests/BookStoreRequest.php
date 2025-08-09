<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:255', 'unique:books,title', 'regex:/\S+/'],
            'author' => ['required', 'string', 'max:100', 'regex:/\S+/'],
            'genre' => ['required', 'string', 'in:fantasy,sci-fi,mystery,drama'],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The title field is required.',
            'title.unique' => 'This title already exists.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'title.regex' => 'The title cannot be empty or contain only spaces.',
            'author.required' => 'The author field is required.',
            'author.max' => 'The author name may not be greater than 100 characters.',
            'author.regex' => 'The author name cannot be empty or contain only spaces.',
            'genre.required' => 'The genre field is required.',
            'genre.in' => 'The selected genre is invalid.',
        ];
    }
}
