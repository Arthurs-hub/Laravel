<?php

namespace App\Models;

use Exception;

class Employee
{
    public $id;
    public $name;
    public $surname;
    public $position;
    public $address;
    public $email;
    public $workData;
    public $jsonData;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Имитация поиска по id.
     * Возвращает объект Employee или выбрасывает исключение.
     */
    public static function findOrFail(int $id): self
    {
        if ($id > 0 && $id <= 1000) {
            return new self([
                'id' => $id,
                'name' => 'ExistingName',
                'surname' => 'ExistingSurname',
                'position' => 'ExistingPosition',
                'address' => 'ExistingAddress',
                'email' => 'existing@example.com',
                'workData' => 'Existing work data',
                'jsonData' => null,
            ]);
        }

        throw new Exception("Employee with id {$id} not found.");
    }
}
