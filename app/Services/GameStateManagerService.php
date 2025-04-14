<?php

namespace App\Services;

use App\Models\Level;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class GameStateManagerService
{
    public function __construct()
    {
    }

    public function getKey(int $studentId , int $levelId){
        return "game:state:{$studentId}:level:{$levelId}";
    }

    public function startLevel(int $studentId, int $levelId,string $gameType,array $wordsIds):void
    {
        Cache::put($this->getKey($studentId,$levelId),[
            'current_word_id' => array_shift($wordsIds),
            'remaining_word_ids' => $wordsIds,
            'score'=>0,
            'game_type'=>$gameType,
        ]);

    }

    public function getState(int $studentId, int $levelId): ?array
    {
        return Cache::get($this->getKey($studentId, $levelId));
    }

    public function updateState(int $studentId,int $levelId,array $newState):void
    {
        Cache::put($this->getKey($studentId,$levelId),$newState);

    }

    public function clearState(int $studentId,int $levelId):void
    {
        Cache::forget($this->getKey($studentId,$levelId));

    }

//    public function markLevelCompleted(int $studentId,int $levelId):void
//    {
//        \DB::table('student_level')->updateOrInsert([
//            'student_id' => $studentId,
//            'level_id' => $levelId,
//        ],[
//            'completed_at' => now(),
//            'updated_at' => now(),
//        ]);
//
//    }


    public function markLevelCompleted(int $studentId, int $levelId): bool // Return true if completed first time
    {
        $now = now();
        // حاول الإضافة أولاً باستخدام whereNull completed_at (لمنع race condition بسيطة)
        // أو استخدم طريقة القفل إذا كان الضغط عالياً جداً
        $inserted = \DB::table('student_level')->insertOrIgnore([
            'student_id' => $studentId,
            'level_id' => $levelId,
            'completed_at' => $now,
            'created_at' => $now, // إضافة created_at هنا مهم للتحقق
            'updated_at' => $now,
        ]);

        if ($inserted) {
            // تمت الإضافة بنجاح (أول مرة)
            $level = Level::find($levelId);
            if ($level && $level->points_reward > 0) {
                $student = Student::find($studentId);
                if ($student) {
                    $student->addPoints($level->points_reward);
                    Log::info("Student {$studentId} completed Level {$levelId} FIRST TIME. Earned {$level->points_reward} points.");
                }
            }
            return true; // اكتمل لأول مرة
        } else {
            // لم يتم الإدراج، ربما موجود مسبقًا (إعادة لعب)
            // يمكنك اختيارياً تحديث updated_at إذا أردت تتبع آخر مرة لعب
            \DB::table('student_level')
                ->where('student_id', $studentId)
                ->where('level_id', $levelId)
                ->update(['updated_at' => $now]); // تحديث وقت التحديث فقط
            Log::info("Student {$studentId} replayed Level {$levelId}. No points awarded.");
            return false; // ليس الإكمال الأول
        }
    }


}
