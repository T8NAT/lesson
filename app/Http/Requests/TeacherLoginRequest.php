<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherLoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email|string|exists:teachers,email',
            'password' => 'required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
