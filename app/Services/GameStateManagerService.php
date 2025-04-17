<?php

namespace App\Services;

use App\Models\Level;
use App\Models\Student;
use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GameStateManagerService
{
    public function __construct()
    {
    }

    public function getKey(int $studentId , int $levelId){
        return "game:state:{$studentId}:level:{$levelId}";
    }

    public function startLevel(int $studentId, int $levelId,string $gameType,array $wordIds):void
    {
        Cache::put($this->getKey($studentId,$levelId),[
            'current_word_id' => array_shift($wordIds),
            'remaining_word_ids' => $wordIds,
            'score'=>0,
            'game_type'=>$gameType,
        ]);
        Log::info("Cache 'put' executed. Checking key existence: " . ($this->getKey($studentId,$levelId)) . " - Exists: " . (Cache::has($this->getKey($studentId,$levelId)) ? 'Yes' : 'No'));
        $testState = Cache::get($this->getKey($studentId, $levelId));
//        dd($testState);
        Log::info("Test retrieval inside service: " . ($testState ? json_encode($testState) : 'NULL'));

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


    public function markLevelCompleted(int $studentId, int $levelId): bool
    {
        $now = now();
        $inserted = \DB::table('student_level')->insertOrIgnore([
            'student_id' => $studentId,
            'level_id' => $levelId,
            'completed_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        if ($inserted) {
            $level = Level::find($levelId);
            if ($level && $level->points_reward > 0) {
                $student = Student::find($studentId);
                if ($student) {
                    $student->addPoints($level->points_reward);
                    Log::info("Student {$studentId} completed Level {$levelId} FIRST TIME. Earned {$level->points_reward} points.");
                }
            }
            return true;
        } else {
            \DB::table('student_level')
                ->where('student_id', $studentId)
                ->where('level_id', $levelId)
                ->update(['updated_at' => $now]);
            Log::info("Student {$studentId} replayed Level {$levelId}. No points awarded.");
            return false;
        }
    }


}
