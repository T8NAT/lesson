<?php

namespace App\Services\Student;

use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\JsonResponse;

class StudentService
{
    public function __construct()
    {
    }

    public function createStudent($data)
    {
        return Student::query()->create($data);
    }

}
