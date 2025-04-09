<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    protected $fillable = [
        'game_id',
        'level_number',
        'name',
        'description',
        'points_reward',
        'is_active',
    ];

    public function game():BelongsTo {
        return $this->belongsTo(Game::class);
    }

    public function CompletedByStudents():BelongsToMany
    {
        return $this->belongsToMany(Student::class,'student_level')->withPivot('completed_at');
    }
}
