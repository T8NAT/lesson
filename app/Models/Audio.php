<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Audio extends Model
{
    protected $table = 'audios';

    protected $fillable = [
        'name',
        'path',
        'description',
    ];

    public function words(): HasMany
    {
        return $this->hasMany(Word::class);
    }
}
