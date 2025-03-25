<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
protected $fillable = [
    'images',
    'game_id'
];
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
