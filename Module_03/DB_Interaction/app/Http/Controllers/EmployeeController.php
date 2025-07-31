<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function store()
    {
        $employee = new Employee();

        $employee->name = 'John Doe';
        $employee->position = 'Developer';

        $employee->save();

        return "Сотрудник успешно сохранён!";
    }
}
