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

        return response()->json([
            'message' => 'Данные успешно получены',
            'data' => $data
        ]);
    }
}
