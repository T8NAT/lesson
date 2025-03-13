<?php

namespace App\Http\Resources;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Student */
class StudentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'terms_and_conditions' => (bool) $this->terms_and_conditions,
            'type' => 'Student',
            'created_at' => $this->created_at->toDateTimeString(),
            'token' => $this->token,

        ];
    }
}
