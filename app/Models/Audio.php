<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Audio extends Model
{
    protected $table = 'audios';

    protected $fillable = [
        'name',
        'path',
        'description',
    ];

    public function word(): HasOne
    {
        return $this->hasOne(Word::class);
    }
}
