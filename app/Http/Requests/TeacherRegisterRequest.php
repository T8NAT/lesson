<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRegisterRequest extends FormRequest
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
            'terms_and_conditions' => 'required|accepted',
            'age'=>'required|integer|min:1|max:100',
            'available_times'=>'required',
            'about'=>'nullable|string',
            'contact_method'=>'nullable|in:phone,meeting',
            'academic_certificate'=>'required|array|max:10000',
            'academic_certificate.*'=>'required|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'experience'=>'required|array|max:10000',
            'experience.*'=>'required|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ];
    }

}
