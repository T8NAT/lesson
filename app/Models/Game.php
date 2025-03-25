<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Game extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'slug',
        'category_id',
        'type_id',
        'status',
        'images',
    ];
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class,'category_game','game_id','category_id');
    }

//    public function words(): HasManyThrough
//    {
//        return $this->hasManyThrough(Word::class, Category::class, 'id','category_id');
//    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }
    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }
}
