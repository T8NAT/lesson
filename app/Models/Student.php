<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Student extends Authenticatable
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
        'address',
        'last_login',
        'terms_and_conditions',
        'type',
        'role_id',
        'points'
    ];

    public function role() : BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function teacher() : BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
    public function CompletedLevels() : BelongsToMany{
        return $this->belongsToMany(Level::class,'student_level')->withPivot('completed_at');
    }
    public function addPoints($points):void
    {
        if ($points > 0){
            $this->increment('points', $points);
        }
    }
}
