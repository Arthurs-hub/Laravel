<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookStoreRequest;
use App\Models\Book;

class BookController extends Controller
{
    public function index()
    {
        return view('form');
    }

    public function store(BookStoreRequest $request)
    {
        $book = new Book();
        $book->title = $request->input('title');
        $book->author = $request->input('author');
        $book->genre = $request->input('genre');
        $book->save();

        return response()->view('success', ['message' => 'Book was successfully validated and saved']);
    }
}
