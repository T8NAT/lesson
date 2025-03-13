<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permission extends Model
{
    protected $guarded = [];

    protected $casts=[
        'permissions'=>'array',
    ];
    public function role():BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
