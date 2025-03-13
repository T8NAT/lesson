<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'first_name'=>'required|string|min:3|max:100',
            'last_name'=>'required|string|min:3|max:100',
            'email'=>'required|email|unique:students,email',
            'password'=>'required|string|min:3|max:50',
            'phone'=>'required|string|min:3|max:15',
            'terms_and_conditions' => 'required|accepted'
        ];
    }

}
