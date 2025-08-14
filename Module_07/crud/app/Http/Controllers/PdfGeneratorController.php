<?php

namespace App\Http\Controllers;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfGeneratorController extends Controller
{
    public function index($id)
    {
        $user = User::find($id);
        if (!$user) {
            abort(404, 'User not found');
        }

        $data = [
            'name' => $user->name,
            'surname' => $user->surname,
            'email' => $user->email,
        ];

        $pdf = Pdf::loadView('resume', $data); // use Pdf instead of PDF

        return $pdf->stream('resume.pdf');
    }
}
