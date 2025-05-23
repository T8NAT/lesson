<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected  $guarded = [];
    public function users():HasMany
    {
        return $this->hasMany(User::class);

    }

    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);

    }

}
