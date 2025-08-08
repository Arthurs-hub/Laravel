<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        return view('employee-form');
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $jsonDataRaw = $request->input('jsonData');
        $jsonData = json_decode($jsonDataRaw);

        $street = $city = $lat = $lng = null;
        if (is_array($jsonData) && isset($jsonData[0]->address)) {
            $street = $jsonData[0]->address->street ?? null;
            $city = $jsonData[0]->address->city ?? null;
            $lat = $jsonData[0]->address->geo->lat ?? null;
            $lng = $jsonData[0]->address->geo->lng ?? null;
        }

        $employee = new Employee([
            'id' => rand(1, 1000),
            'name' => $request->input('name'),
            'surname' => $request->input('surname'),
            'position' => $request->input('position'),
            'address' => $request->input('address'),
            'email' => $request->input('email'),
            'workData' => $request->input('workData'),
            'jsonData' => $jsonDataRaw,
        ]);

        return response()->json([
            'id' => $employee->id,
            'name' => $employee->name,
            'surname' => $employee->surname,
            'position' => $employee->position,
            'address' => $employee->address,
            'email' => $employee->email,
            'workData' => $employee->workData,
            'jsonData' => $jsonData,
            'street' => $street,
            'city' => $city,
            'lat' => $lat,
            'lng' => $lng,
            'path' => $request->path(),
            'url' => $request->url(),
        ]);
    }

    public function update(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $employeeId = $id;
        $employee = Employee::findOrFail($employeeId);

        $jsonDataRaw = $request->input('jsonData');
        $jsonData = json_decode($jsonDataRaw);

        $street = $city = $lat = $lng = null;
        if (is_array($jsonData) && isset($jsonData[0]->address)) {
            $street = $jsonData[0]->address->street ?? null;
            $city = $jsonData[0]->address->city ?? null;
            $lat = $jsonData[0]->address->geo->lat ?? null;
            $lng = $jsonData[0]->address->geo->lng ?? null;
        }

        $employee->name = $request->input('name');
        $employee->surname = $request->input('surname');
        $employee->position = $request->input('position');
        $employee->address = $request->input('address');
        $employee->email = $request->input('email');
        $employee->workData = $request->input('workData');
        $employee->jsonData = $jsonDataRaw;

        return response()->json([
            'id' => $employee->id,
            'name' => $employee->name,
            'surname' => $employee->surname,
            'position' => $employee->position,
            'address' => $employee->address,
            'email' => $employee->email,
            'workData' => $employee->workData,
            'jsonData' => $jsonData,
            'street' => $street,
            'city' => $city,
            'lat' => $lat,
            'lng' => $lng,
            'path' => $request->path(),
            'url' => $request->url(),
        ]);
    }

    public function getPath(Request $request): string
    {
        return $request->path();
    }

    public function getUrl(Request $request): string
    {
        return $request->url();
    }

    public function getEmployeeData(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->only(['name', 'surname', 'position', 'address', 'email', 'workData', 'jsonData']);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
