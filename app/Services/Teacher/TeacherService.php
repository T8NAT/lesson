<?php

namespace App\Services\Teacher;

use App\Models\Teacher;

class TeacherService
{
    public function __construct()
    {
    }
    public function createTeacher($data){
        return Teacher::query()->create($data);
    }



}
