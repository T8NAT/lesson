<?php

namespace App\Http\Resources;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/** @mixin Image */
class ImagesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'images'=>url(Storage::url($this->images))
        ];
    }
}
