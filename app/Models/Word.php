<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Word extends Model
{
    protected $fillable = [
        'word',
        'category_id',
        'image_id',
        'audio_id',
    ];

//    protected $casts = [
//        'word' => 'array',
//    ];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);

    }
    public function image(): BelongsTo{
        return $this->belongsTo(Image::class);
    }
    public function audio(): BelongsTo
    {
        return $this->belongsTo(Audio::class);
    }

    public function levels(): BelongsToMany
    {
        return $this->belongsToMany(Level::class, 'level_word');

    }

}
