<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
protected $fillable = [
    'name',
    'image',
];
    public function word(): BelongsTo
    {
        return $this->belongsTo(Word::class);
    }
}
