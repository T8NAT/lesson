<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Teacher extends Authenticatable
{
    use HasApiTokens;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'avatar',
        'gender',
        'status',
        'phone',
        'age',
        'address',
        'last_login',
        'terms_and_conditions',
        'type',
        'role_id',
        'academic_certificate',
        'experience'

    ];

    public function role() : BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
