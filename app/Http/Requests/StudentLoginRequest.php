<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentLoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email|string|exists:students,email',
            'password' => 'required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
