<?php

namespace App\Http\Resources;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/** @mixin Game */
class GameResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'icon' => url(Storage::url($this->icon)),
            'description' => $this->description,
            'status' => $this->status,
            'type' => TypeResource::make($this->type),
            'images' => ImagesResource::collection($this->images),
        ];
    }
}
