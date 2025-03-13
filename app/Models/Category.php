<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'slug',
        'icon',

    ];

    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'category_game', 'category_id', 'game_id');
    }
    public function words(){
        return $this->hasMany(Word::class);
    }

}
