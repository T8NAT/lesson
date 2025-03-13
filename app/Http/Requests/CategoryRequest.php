<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        $id = $this->route('category');
        return [
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                $this->isMethod('put') ? 'unique:categories,slug,'. $id : 'unique:categories,slug',
            ],
            'icon' => 'required|max:1048',
            'description' => 'nullable|string',
        ];
    }
}
