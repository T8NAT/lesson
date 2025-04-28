<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LevelRequest extends FormRequest
{
    public function rules(): array
    {
        $id = $this->route('level');

        return [
            'name'          => ['required', "unique:levels,name,{$id},id",'string', 'max:255'],
            'game_id'       => 'required|array|exists:games,id',
            'game_id.*'     => 'exists:games,id',
            'category_id'   => 'required|exists:categories,id',
            'level_number'  => 'required|numeric|max:255',
            'is_active'     => 'in:on',
            'description'   => 'nullable|string|max:255',
            'points_reward' => 'nullable|numeric|max:255',
            'word_id'       => 'required|array|exists:words,id',
            'word_id.*'     => 'exists:words,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
