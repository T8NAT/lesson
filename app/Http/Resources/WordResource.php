<?php

namespace App\Http\Resources;

use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Word */
class WordResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'words' => $this->words,
            'category_id' => $this->category_id,
            'category' => new CategoryResource($this->whenLoaded('category')),
        ];
    }
}
