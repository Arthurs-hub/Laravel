<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('employee-form');
    }

    public function store(Request $request)
    {

        $name = $request->input('name');
        $surname = $request->input('surname');
        $position = $request->input('position');
        $address = $request->input('address');
        $email = $request->input('email');
        $workData = $request->input('workData');
        $jsonDataRaw = $request->input('jsonData');

        $jsonData = json_decode($jsonDataRaw, true);

        $street = $city = $lat = $lng = null;
        if (is_array($jsonData) && isset($jsonData[0]['address'])) {
            $street = $jsonData[0]['address']['street'] ?? null;
            $city = $jsonData[0]['address']['city'] ?? null;
            $lat = $jsonData[0]['address']['geo']['lat'] ?? null;
            $lng = $jsonData[0]['address']['geo']['lng'] ?? null;
        }

        $path = $request->path();
        $url = $request->url();

        $id = rand(1, 1000);

        return response()->json([
            'id' => $id,
            'name' => $name,
            'surname' => $surname,
            'position' => $position,
            'address' => $address,
            'email' => $email,
            'workData' => $workData,
            'jsonData' => $jsonData,
            'street' => $street,
            'city' => $city,
            'lat' => $lat,
            'lng' => $lng,
            'path' => $path,
            'url' => $url,
        ]);
    }

    public function update(Request $request, $id)
    {

        $name = $request->input('name');
        $surname = $request->input('surname');
        $position = $request->input('position');
        $address = $request->input('address');
        $email = $request->input('email');
        $workData = $request->input('workData');
        $jsonDataRaw = $request->input('jsonData');

        $jsonData = json_decode($jsonDataRaw, true);

        $street = $city = $lat = $lng = null;
        if (is_array($jsonData) && isset($jsonData[0]['address'])) {
            $street = $jsonData[0]['address']['street'] ?? null;
            $city = $jsonData[0]['address']['city'] ?? null;
            $lat = $jsonData[0]['address']['geo']['lat'] ?? null;
            $lng = $jsonData[0]['address']['geo']['lng'] ?? null;
        }

        $path = $request->path();
        $url = $request->url();

        return response()->json([
            'id' => $id,
            'name' => $name,
            'surname' => $surname,
            'position' => $position,
            'address' => $address,
            'email' => $email,
            'workData' => $workData,
            'jsonData' => $jsonData,
            'street' => $street,
            'city' => $city,
            'lat' => $lat,
            'lng' => $lng,
            'path' => $path,
            'url' => $url,
        ]);
    }

    public function getPath(Request $request)
    {
        return $request->path();
    }

    public function getUrl(Request $request)
    {
        return $request->url();
    }

    public function getEmployeeData(Request $request)
    {

        $data = $request->only(['name', 'surname', 'position', 'address', 'email', 'workData', 'jsonData']);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
