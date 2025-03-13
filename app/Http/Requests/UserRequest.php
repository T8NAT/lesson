<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
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
        $id = $this->route('user');
        return [
            'first_name' => [
                'required',
                'string',
                'max:255',
                'min:3',
            ],
            'last_name' => [
                'required',
                'string',
                'max:255',
                'min:3',
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($id),
            ],
            'password' =>  [
                $this->isMethod('PUT') ? 'sometimes' : 'required',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],

            'phone' => 'required|numeric',
            'gender' => 'required|in:male,female',
            'role_id' => 'int|exists:roles,id',
            'status' => 'in:active,inactive,blocked',
            'avatar' => 'nullable|image',
            'type'=>'in:student,teacher',
        ];
    }
}
