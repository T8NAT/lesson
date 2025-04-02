<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Word extends Model
{
    protected $fillable = [
        'word',
        'category_id',
        'image_id',
    ];

//    protected $casts = [
//        'word' => 'array',
//    ];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);

    }
    public function word(): BelongsTo{
        return $this->belongsTo(Word::class);
    }
}
