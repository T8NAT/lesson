<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Word extends Model
{
    protected $fillable = [
        'words',
        'category_id',
    ];

    protected $casts = [
        'words' => 'array',
    ];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);

    }
}
