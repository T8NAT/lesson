<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;

class UserRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name'=>'required|string|min:3|max:100',
            'last_name'=>'required|string|min:3|max:100',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|string|min:3|max:50',
            'phone'=>'required|string|min:3|max:15',
            'terms_and_conditions' => 'required|accepted'
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->has('password'))
                $this->merge(
                    [
                        'password' => Hash::make($this->input('password'))
                    ]);
        });

    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'=>false,
            'message'=>$validator->errors()->first(),
            'error'=>$validator->errors()

        ],422)

        );
    }
}
