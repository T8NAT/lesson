<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
            'name'     => 'required|string',
            'logo'     => [$this->isMethod('PUT') ? 'nullable' : 'required','mimes:jpeg,JPEG,jpg,JPG,png,PNG,gif,GIf', 'image'],
            'favicon'  => 'nullable|image|mimes:svg,SVG,png,PNG',
            'phone' => 'required|numeric',
            'email' => 'required|email',
            'about' => 'required|string',
            'url'   => 'required|url',
            'linkedin'  => 'nullable|url',
            'facebook'  => 'nullable|url',
            'instagram' => 'nullable|url',
            'x'         => 'nullable|url',

        ];
    }
}
