<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    protected $table = 'levels';
    protected $fillable = [
        'level_number',
        'name',
        'description',
        'points_reward',
        'is_active',
        'category_id',
    ];

    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'game_level');
    }

    public function completedByStudents(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_level')->withPivot('completed_at');
    }


    /**
     * فحص المرحلة إذا مكتملة لطالب معين
     */
    public function isCompletedBy(Student $student): bool
    {
        return $this->completedByStudents()->where('student_id', $student->id)->exists();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }


}
