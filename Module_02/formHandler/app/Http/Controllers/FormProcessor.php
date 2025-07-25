<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormProcessor extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function store(Request $request)
    {
        $data = $request->only(['name', 'surname', 'email']);

        return view('greeting', ['name' => $data['name']]);
    }
}
