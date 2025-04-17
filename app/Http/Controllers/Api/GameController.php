<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ControllerHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\GameResource;
use App\Models\Category;
use App\Models\Game;
use App\Models\Level;
use App\Models\Student;
use App\Models\Word;
use App\Services\GameStateManagerService;
use App\Services\ImageRecognitionService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class GameController extends Controller
{

    protected $gameStateManager;
    protected $imageRecognitionService;

    public function __construct(GameStateManagerService $gameStateManager,ImageRecognitionService $imageRecognitionService)
    {
        $this->gameStateManager = $gameStateManager;
        $this->imageRecognitionService = $imageRecognitionService;
    }
    public function index()
    {
        $games = Game::query()->latest()->get();
        $games_data = GameResource::collection($games);
        return ControllerHelper::generateResponseApi(true, 'تم جلب كافة الالعاب بنجاح', $games_data, 200);
    }


    public function checkGame(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'game_id' => 'required|exists:games,id',
            'category_id' => 'required|exists:categories,id',
            'student_id' => 'required|exists:students,id',

        ]);

        if ($validator->fails()) {
            return ControllerHelper::generateResponseApi(false, 'خطأ في البيانات المدخلة', $validator->errors(), 422);
        }

        $game = Game::findOrFail($request->game_id);
        $category = $game->categories()->where('categories.id', $request->category_id)->first();
        if (!$category) {
            return ControllerHelper::generateResponseApi(false, 'القسم غير مرتبط بهذه اللعبة.', null, 404);
        }

        $gameType = $game->type;
        $gameType = $gameType->name;

        switch ($gameType) {
            case 'كلمات':
                return $this->handleWordsGame($game, $category, $request->student_id);
            case 'صورة وكلمات':
                return $this->handleImageWordsGame($game, $category, $request->student_id);
            case 'صوت':
                return $this->handleSoundGame($game, $category, $request->student_id);
            default:
                \Log::error("Unsupported game type: ".$gameType);
                return ControllerHelper::generateResponseApi(false, 'نوع اللعبة غير مدعوم حاليًا.', null, 422);
        }

    }

//    private function handleWordsGame($game, $category, $student_id)
//    {
//        $allWords = Cache::remember("student_{$student_id}_category_{$category->id}_words", 600,
//            function () use ($category) {
//                return $category->words()->pluck('word')->shuffle()->values();
//            });
//
//        if ($allWords->isEmpty()) {
//            return ControllerHelper::generateResponseApi(false, 'لا توجد كلمات مرتبطة بهذا القسم', null, 404);
//        }
//
//        $firstLevel = Level::where('category_id', $category->id)
//            ->whereHas('games', function ($query) use ($game) {
//                $query->where('games.id', $game->id);
//            })
//            ->orderBy('level_number')
//            ->first();
//
//        if (!$firstLevel) {
//            return ControllerHelper::generateResponseApi(false, 'لا توجد مراحل مرتبطة بهذا القسم وهذه اللعبة', null,
//                404);
//        }
//
//        $randomWord = $allWords->random();
//        $wordsRemaining = $allWords->diff([$randomWord])->values()->toArray();
//
//        $gameState = [
//            'game_type' => 'كلمات',
//            'level_id' => $firstLevel->id,
//            'category_id' => $category->id,
//            'words_remaining' => $wordsRemaining,
//            'used_words' => [$randomWord],
//            'current_word' => $randomWord,
//            'score' => 0
//        ];
//
//        Cache::put("student_{$student_id}_game_state", $gameState, now()->addMinutes(10));
////        dd(Cache::get("student_{$student_id}_game_state"));
//
//        $data = [
//            'game_name' => $game->name,
//            'category_name' => $category->name,
//            'random_word' => $randomWord,
//            'level' => $firstLevel->name,
//        ];
//
//        return ControllerHelper::generateResponseApi(true, 'تم تشغيل لعبة البحث عن الأسماء بنجاح', $data, 200);
//    }

//    private function handleImageWordsGame($game, $category, $student_id)
//    {
//        $possibleCorrectItems = Word::where('category_id', $category->id)
//            ->whereNotNull('image_id')
//            ->with('image')
//            ->get();
//
//        if ($possibleCorrectItems->isEmpty()) {
//            return ControllerHelper::generateResponseApi(false, 'لا توجد كلمات مرتبطة بصور في هذا القسم لبدء اللعبة.',
//                null, 404);
//        }
//
//        $firstLevel = Level::where('category_id', $category->id)
//            ->whereHas('games', function ($query) use ($game) {
//                $query->where('games.id', $game->id);
//            })
//            ->orderBy('level_number')
//            ->first();
//
//        if (!$firstLevel) {
//            return ControllerHelper::generateResponseApi(false, 'لا توجد مراحل مرتبطة بهذا القسم وهذه اللعبة', null,
//                404);
//        }
//
//        $allWordsInCategory = Word::where('category_id', $category->id)->pluck('word');
//
//        if ($allWordsInCategory->count() < 4) {
//            return ControllerHelper::generateResponseApi(false,
//                'لا يوجد عدد كافٍ من الكلمات المختلفة في هذا القسم للعب (مطلوب 4 على الأقل).', null, 400);
//        }
//
//        $levelWords = $possibleCorrectItems->pluck('word')->shuffle()->values();
//        $currentWord = $levelWords->first();
//        $currentItem = $possibleCorrectItems->where('word', $currentWord)->first();
//
//        $otherWords = $allWordsInCategory->filter(fn($w) => $w !== $currentWord)->shuffle()->take(3);
//        $words = collect([$currentWord])->merge($otherWords)->shuffle();
//
//        $gameState = [
//            'game_type' => 'صورة وكلمات',
//            'level_id' => $firstLevel->id,
//            'category_id' => $category->id,
//            'remaining_words' => $levelWords->toArray(),
//            'correct_answer' => $currentWord
//        ];
//
//        Cache::put("student_{$student_id}_game_state", $gameState, now()->addMinutes(10));
//
//        return ControllerHelper::generateResponseApi(true, 'تم تشغيل لعبة صورة وكلمات بنجاح', [
//            'game' => $game->name,
//            'level' => $firstLevel->name,
//            'image_url' => url(Storage::url($currentItem->image->image)),
//            'words' => $words->values()->all(),
//            'correct_word' => $currentWord,
//        ]);
//    }

//    private function handleSoundGame($game, $category, $student_id)
//    {
//        $possibleCorrectItems = Word::where('category_id', $category->id)
//            ->whereNotNull('audio_id')
//            ->with('audio')
//            ->get();
//
//        if ($possibleCorrectItems->isEmpty()) {
//            return ControllerHelper::generateResponseApi(false,
//                'لا توجد مفردات مرتبطة بملف صوتي في هذا القسم لبدء اللعبة.', null, 404);
//        }
//
//        $firstLevel = Level::query()->where('category_id', $category->id)
//            ->whereHas('games', function ($query) use ($game) {
//                $query->where('games.id', $game->id);
//            })->orderBy('level_number')
//            ->first();
//
//
//        $correctItem = $possibleCorrectItems->random();
//        $correctWord = $correctItem->word;
//        $levelWords = $possibleCorrectItems->pluck('word')->shuffle()->values();
//
//        $correctItem->loadMissing('audio');
//
//        if (!$correctItem->audio || empty($correctItem->audio->path)) {
//            Log::error("Audio relationship or path is missing for VocabularyItem ID: ".$correctItem->id);
//            return ControllerHelper::generateResponseApi(false, 'حدث خطأ: لم يتم العثور على ملف الصوت المرتبط.', null,
//                500);
//        }
//        $correctAudioPath = $correctItem->audio->path;
//
//        if (Auth::guard('student')->check()) {
//            $gameState = [
//                'game_type' => 'صوت',
//                'level_id' => $firstLevel->id,
//                'category_id' => $category->id,
//                'remaining_words' => $levelWords->toArray(),
//                'correct_answer' => $correctWord,
//                'score' => 0
//            ];
//            Cache::put("student_{$student_id}_game_state", $gameState, now()->addMinutes(10));
//        }
////                dd(Cache::get("student_{$student_id}_game_state"));
//        $data = [
//            'game' => $game->name,
//            'category_name' => $category->name,
//            'level_name' => $firstLevel->name,
//            'audio_url' => url(Storage::url($correctAudioPath)),
//            'correct_answer' => $correctWord
//        ];
//        return ControllerHelper::generateResponseApi(true, 'تم تشغيل لعبة المحادثات بنجاح', $data, 200);
//
//    }


//    protected function completeLevel(int $studentId, int $levelId): bool
//    {
//        $student = Student::find($studentId);
//        $level = Level::find($levelId);
//
//        if (!$student || !$level) {
//            Log::error("Attempted to complete level for invalid student ({$studentId}) or level ({$levelId}).");
//            return false;
//        }
//
//        $syncResult = $student->completedLevels()->syncWithoutDetaching([$levelId => ['completed_at' => now()]]);
//
//        if (!empty($syncResult['attached'])) {
//            $student->addPoints($level->points_reward);
//            Log::info("Student {$studentId} completed Level {$levelId} and earned {$level->points_reward} points. Total points: {$student->points}");
//            return true;
//        } else {
//            Log::info("Student {$studentId} replayed Level {$levelId}. No points awarded.");
//            return true;
//        }
//    }
//
////    public function checkAnswer(Request $request)
////    {
////       $validator = Validator::make($request->all(), [
////           'answer' => 'required|string|max:191',
////       ]);
////       if ($validator->fails()) {
////           return ControllerHelper::generateResponseApi(false,'الاجابة مطلوبة',$validator->errors()->all(), 422);
////       }
////       if (!Auth::guard('student')->check()) {
////           return ControllerHelper::generateResponseApi(false,'المستخدم غير مسجل للدخول', null, 401);
////       }
////       $studentId = Auth::guard('student')->user()->id;
////       $gameState = Cache::get("student_{$studentId}_game_state");
////       if (empty($gameState)) {
////           return ControllerHelper::generateResponseApi(false,' لم يتم العثور على لعبة نشطة، او انتهت  مدة الجلسة !', null, 404);
////       }
////       $studentAnswer = strtolower(trim($request->input('answer')));
////       $correctAnswer = strtolower(trim($gameState['correct_answer']));
////       $levelId = $gameState['level_id'];
////       $remainingWords = $gameState['remaining_words'];
////
////       $isCorrect = ($studentAnswer === $correctAnswer);
////
////       if ($isCorrect) {
////           $remainingWords = array_values(array_filter($remainingWords, fn($word) => strtolower(trim($word)) !== $correctAnswer));
////           if (empty($remainingWords)) {
////               $level = Level::query()->find($levelId);
////               Student::query()->find($studentId)->addPoints($level->points_reward);
////               $nextLevel = $this->getNextLevel($levelId, $gameState['category_id']);
////               if ($nextLevel) {
////                   $possibleWords = Word::where('category_id', $gameState['category_id'])
////                   ->whereNotNull('image_id')
////                   ->with('image')
////                   ->pluck('word')
////                   ->shuffle()
////                   ->values();
////                   $newWord = $possibleWords->first();
////                   $gameState= [
////                     'game_type' => 'صورة وكلمات',
////                       'level_id' => $nextLevel->id,
////                       'category_id' => $gameState['category_id'],
////                       'remaining_words' => $possibleWords->toArray(),
////                       'correct_answer' => $newWord,
////                   ];
////                   Cache::put("student_{$studentId}_game_state", $gameState, now()->addMinutes(10));
////                   $data = [
////                       'next_level' => $nextLevel->name,
////                       'points_awarded' => $level->points_reward,
////                   ];
////                   return ControllerHelper::generateResponseApi(true,'اجابة صحيحة! انتقلت للمرحلة التالية',$data);
////               }else{
////                   Cache::Forget("student_{$studentId}_game_state");
////                   $data = [
////                       'points_awarded' => $level->points_reward,
////                   ];
////                   return ControllerHelper::generateResponseApi(true,'اجابة صحيحة! لقد اكملت اللعبة بالكامل.أحسنت',$data);
////               }
////
////           }else{
////               $newWord = $remainingWords[0];
////               $correctItem = Word::where('word', $newWord)
////                   ->where('category_id', $gameState['category_id'])
////                   ->with('image')->first();
////               $otherWords = Word::where('category_id', $gameState['category_id'])
////                   ->pluck('word')->filter(fn($w) => $w !== $newWord)->shuffle()->take(3);
////
////               $words = collect([$newWord])->merge($otherWords)->shuffle();
////               $gameState['correct_answer'] = $newWord;
////               $gameState['remaining_words'] = $remainingWords;
////               Cache::put("student_{$studentId}_game_state", $gameState, now()->addMinutes(10));
////               $data = [
////                   'image_url' => url(Storage::url($correctItem->image->image)),
////                   'words' => $words->values()->all(),
////                   'correct_word' => $newWord,
////               ];
////               return ControllerHelper::generateResponseApi(true, 'إجابة صحيحة! إليك صورة جديدة.', $data);
////           }
////       }else{
////           return ControllerHelper::generateResponseApi(false, 'إجابة خاطئة. حاول مرة أخرى!', ['correct_answer' => $correctAnswer], 422);
////
////       }
////    }
//
//
//    public function checkAnswer(Request $request,)
//    {
//        $student_id = Auth::guard('student')->user()->id;
//        $answer = $request->input('answer');
//        $labels = $request->input('labels', []);
//
//        $gameState = Cache::get("student_{$student_id}_game_state");
//
//        if (!$gameState) {
//            return ControllerHelper::generateResponseApi(false, 'لم تبدأ اللعبة بعد.', [], 400);
//        }
//
//        $gameType = $gameState['game_type'] ?? 'كلمات';
//        $currentWord = $gameState['current_word'] ?? null;
//
//        if (!$currentWord) {
//            return ControllerHelper::generateResponseApi(false, 'لا توجد كلمة حالية في اللعبة.', [], 400);
//        }
//
//        $wasCorrect = $this->validateAnswer($gameType, $currentWord, $answer, $labels);
//
//        if ($wasCorrect) {
//            $this->completeLevel($student_id, $gameState['level_id']);
//            $gameState['words_remaining'] = array_diff($gameState['words_remaining'], [$currentWord]);
//        }
//
//        return $this->nextWordOrLevel($student_id, $gameState, $wasCorrect, $labels);
//    }
//
//    protected function validateAnswer(string $gameType, string $target, $input, ImageRecognitionService $imageService): bool
//    {
//        switch ($gameType) {
//            case 'كلمات':
//                return Str::lower(trim($target)) === Str::lower(trim($input));
//            case 'صورة وكلمات':
//                return $this->validateImageAgainstWord($target, $input); // مثال: استخدم Google Vision
//            case 'صوت':
//                return $this->validateAudioAgainstWord($target, $input); // لو عندك تحقق صوتي
//            default:
//                return false;
//        }
//    }
//
////public function checkAnswer(Request $request)
////{
////    $student_id = Auth::guard('student')->user()->id;
////    $answer = $request->input('answer');
////
////    $gameState = Cache::get("student_{$student_id}_game_state");
////
////    if (!$gameState) {
////        return ControllerHelper::generateResponseApi(false,'لم يتم العثور على حالة اللعبة', null, 404);
////    }
////
////    $correctAnswer = $gameState['correct_answer'] ?? null;
////    $remainingWords = collect($gameState['remaining_words'] ?? []);
////    $isCorrect = ($answer === $correctAnswer);
////    if ($isCorrect) {
////        $remainingWords = $remainingWords->filter(fn ($word) => $word !== $correctAnswer);
////        $gameState['remaining_words'] = $remainingWords->toArray();
////        if (isset($gameState['score'])) {
////            $level = Level::query()->find($gameState['level_id']);
////            $reward = $level->points_reward;
////            $gameState['score'] += $reward;
////
////        }
////        if ($remainingWords->isEmpty()) {
////            $this->completeLevel($student_id, $gameState['level_id']);
////            Cache::forget("student_{$student_id}_game_state");
////
////            $data=[
////                'completed' => true,
////                'score' => $gameState['score'] ?? null,
////            ];
////            return ControllerHelper::generateResponseApi(true,'تهانينا، انهيت المرحلة بنجاح', $data, 200);
////        }
////        $newCorrectAnswer = $remainingWords->random();
////        $gameState['correct_answer'] = $newCorrectAnswer;
////
////        Cache::put("student_{$student_id}_game_state", $gameState, now()->addMinutes(10));
////
////        $response = [
////            'correct' => true,
////            'next_word' => $newCorrectAnswer,
////        ];
////        if ($gameState['game_type'] === 'صوت'){
////            $audioPath = Word::where('word', $newCorrectAnswer)->with('audio')->first()?->audio->path ?? null;
////            $response['audio_url'] = url(Storage::url($audioPath));
////        }
////        if($gameState['game_type'] === 'صورة وكلمات'){
////            $imagePath = Word::where('word', $newCorrectAnswer)->with('image')->first()?->image->path ?? null;
////            $response['image_url'] = url(Storage::url($imagePath));
////        }
////        return ControllerHelper::generateResponseApi(true,'اجابة صحيحة،اليك التحدي التالي',$response);
////
////    }
////    return ControllerHelper::generateResponseApi(false,'اجابة غير صحيحية حاول مرة اخرى',[
////        'correct'=>false,
////        'correct_answer'=>$correctAnswer,
////    ]);
////
////
////}
//    private function nextWordOrLevel($studentId, $gameState, $wasCorrect, $labels)
//    {
//        $remainingWords = $gameState['words_remaining'] ?? [];
//
//        if (empty($remainingWords)) {
//            $nextLevel = $this->getNextLevel($gameState['level_id'], $gameState['category_id']);
//
//            if (!$nextLevel) {
//                Cache::forget("student_{$studentId}_game_state");
//                $student = Student::find($studentId);
//                $student->addPoints($gameState['score']);
//                return ControllerHelper::generateResponseApi(true, 'مبروك! لقد أنهيت اللعبة!', ['score' => $gameState['score']], 200);
//            }
//
//            $newWords = Word::where('category_id', $gameState['category_id'])->pluck('word')->toArray();
//
//            $nextWord = Arr::random($newWords);
//
//            $gameState = [
//                'game_type' => 'كلمات',
//                'level_id' => $nextLevel->id,
//                'category_id' => $gameState['category_id'],
//                'words_remaining' => $newWords,
//                'used_words' => [$nextWord],
//                'current_word' => $nextWord,
//                'score' => $gameState['score'],
//            ];
//
//            Cache::put("student_{$studentId}_game_state", $gameState, now()->addMinutes(10));
//
//            $student = Student::find($studentId);
//            $student->addPoints($nextLevel->points_reward);
//
//            return ControllerHelper::generateResponseApi(true, 'انتقلت إلى المرحلة التالية!', [
//                'match' => $wasCorrect,
//                'next_word' => $nextWord,
//                'level' => $nextLevel->name,
//                'score' => $gameState['score']
//            ], 200);
//        } else {
//            $nextWord = Arr::random($remainingWords);
//
//            $gameState['current_word'] = $nextWord;
//            $gameState['words_remaining'] = array_diff($remainingWords, [$nextWord]);
//
//            Cache::put("student_{$studentId}_game_state", $gameState, now()->addMinutes(10));
//
//            return ControllerHelper::generateResponseApi(true, $wasCorrect ? 'أحسنت! الصورة تطابق الكلمة.' : 'للأسف! الصورة لا تطابق الكلمة.', [
//                'match' => $wasCorrect,
//                'next_word' => $nextWord,
//                'level' => Level::find($gameState['level_id'])->name,
//                'score' => $gameState['score'],
//                'labels' => $wasCorrect ? null : $labels
//            ], $wasCorrect ? 200 : 400);
//        }
//    }
//
//    private function getNextLevel($currentLevelId, $categoryId)
//    {
//        $current = Level::find($currentLevelId);
//        if (!$current) return null;
//
//        return Level::query()
//            ->where('category_id', $categoryId)
//            ->where('level_number', '>', $current->level_number)
//            ->orderBy('level_number')
//            ->first();
//    }
//
//
//    public function checkImage(Request $request, ImageRecognitionService $imageService)
//    {
//        $request->validate([
//            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
//        ]);
//
//        if (!Auth::guard('student')->check()) {
//            return ControllerHelper::generateResponseApi(false, 'المستخدم غير مسجل للدخول.', null, 401);
//        }
//
//        $studentId = Auth::guard('student')->id();
//
//        $gameState = Cache::get("student_{$studentId}_game_state");
//
////        dd($gameState);
//
//        if (!$gameState || $gameState['game_type'] !== 'كلمات') {
//            return ControllerHelper::generateResponseApi(false, 'لم يتم العثور على لعبة نشطة أو انتهت مدة الجلسة.', null, 404);
//        }
//
//        $correctWord = $gameState['current_word'];
//        $imageFile = $request->file('image');
//        $imagePath = Storage::disk('public')->put('temp_images', $imageFile);
//        $fullImagePath = storage_path('app/public/' . $imagePath);
//
//        $labels = $imageService->analyzeImage($fullImagePath);
//        Storage::disk('public')->delete($imagePath);
//
//        $isMatch = false;
//        if (!empty($labels)) {
//            foreach ($labels as $label) {
//                if (isset($label['description']) && strtolower($label['description']) == strtolower($correctWord)) {
//                    $isMatch = true;
//                    break;
//                }
//            }
//        }
//
//        if ($isMatch) {
//            // زيادة النتيجة إذا كانت الإجابة صحيحة
//            $gameState['score'] = ($gameState['score'] ?? 0) + 1;
//        }
//
//        return $this->nextWordOrLevel($studentId, $gameState, $isMatch, $labels);
//    }
//}


//    private function handleWordsGame($game, $category, $student_id)
//    {
//        $gameService = $this->gameStateManager;
//        $allWords = Cache::remember("category_{$category->id}_words", now()->addHours(6), function () use ($category) {
//            return Word::where('category_id', $category->id)->pluck('word')->shuffle();
//        });
//
//        if ($allWords->isEmpty()) {
//            return ControllerHelper::generateResponseApi(false, 'لا توجد كلمات مرتبطة بهذا القسم', null, 404);
//        }
//
//        // استرجاع أول مرحلة مرتبطة بالقسم واللعبة
//        $firstLevel = Level::where('category_id', $category->id)
//            ->whereHas('games', function ($query) use ($game) {
//                $query->where('games.id', $game->id);
//            })
//            ->orderBy('level_number')
//            ->first();
//
//        if (!$firstLevel) {
//            return ControllerHelper::generateResponseApi(false, 'لا توجد مراحل مرتبطة بهذا القسم وهذه اللعبة', null, 404);
//        }
//
//        // اختيار كلمة عشوائية واستبعادها من القائمة
//        $randomWord = $allWords->random();
//        $wordsRemaining = $allWords->diff([$randomWord])->values()->toArray();
//
//        // تخزين حالة اللعبة
//        $state = [
//            'game_type' => 'كلمات',
//            'level_id' => $firstLevel->id,
//            'category_id' => $category->id,
//            'words_remaining' => $wordsRemaining,
//            'used_words' => [$randomWord],
//            'current_word' => $randomWord,
//            'score' => 0
//        ];
//
//        $gameService->updateState($student_id, $firstLevel->id, $state);
//
//        $data = [
//            'game_name' => $game->name,
//            'category_name' => $category->name,
//            'random_word' => $randomWord,
//            'level' => $firstLevel->name,
//        ];
//
//        return ControllerHelper::generateResponseApi(true, 'تم تشغيل لعبة البحث عن الأسماء بنجاح', $data, 200);
//    }

//    private function handleImageWordsGame($game, $category, $student_id)
//    {
//        $gameService = new GameStateManagerService();
//
//        // جلب الكلمات التي تحتوي على صور
//        $possibleCorrectItems = Word::where('category_id', $category->id)
//            ->whereNotNull('image_id')
//            ->with('image')
//            ->get();
//
//        if ($possibleCorrectItems->isEmpty()) {
//            return ControllerHelper::generateResponseApi(false, 'لا توجد كلمات مرتبطة بصور في هذا القسم لبدء اللعبة.', null, 404);
//        }
//
//        // جلب أول مرحلة مرتبطة بالقسم واللعبة
//        $firstLevel = Level::where('category_id', $category->id)
//            ->whereHas('games', function ($query) use ($game) {
//                $query->where('games.id', $game->id);
//            })
//            ->orderBy('level_number')
//            ->first();
//
//        if (!$firstLevel) {
//            return ControllerHelper::generateResponseApi(false, 'لا توجد مراحل مرتبطة بهذا القسم وهذه اللعبة', null, 404);
//        }
//
//        // التحقق من توفر 4 كلمات على الأقل
//        $allWordsInCategory = Word::where('category_id', $category->id)->pluck('word');
//
//        if ($allWordsInCategory->count() < 4) {
//            return ControllerHelper::generateResponseApi(false, 'لا يوجد عدد كافٍ من الكلمات المختلفة في هذا القسم للعب (مطلوب 4 على الأقل).', null, 400);
//        }
//
//        // تحضير الكلمات للمرحلة
//        $levelWords = $possibleCorrectItems->pluck('word')->shuffle()->values();
//        $currentWord = $levelWords->first();
//        $currentItem = $possibleCorrectItems->where('word', $currentWord)->first();
//
//        // تجهيز كلمات الاختيار (واحدة صحيحة + ٣ عشوائية)
//        $otherWords = $allWordsInCategory->filter(fn($w) => $w !== $currentWord)->shuffle()->take(3);
//        $words = collect([$currentWord])->merge($otherWords)->shuffle();
//
//        // حفظ حالة اللعبة في السيرفس
//        $state = [
//            'game_type' => 'صورة وكلمات',
//            'level_id' => $firstLevel->id,
//            'category_id' => $category->id,
//            'remaining_words' => $levelWords->toArray(),
//            'correct_answer' => $currentWord
//        ];
//
//        $gameService->updateState($student_id, $firstLevel->id, $state);
//
//        return ControllerHelper::generateResponseApi(true, 'تم تشغيل لعبة صورة وكلمات بنجاح', [
//            'game' => $game->name,
//            'level' => $firstLevel->name,
//            'image_url' => url(Storage::url($currentItem->image->image)),
//            'words' => $words->values()->all(),
//            'correct_word' => $currentWord,
//        ]);
//    }
//
//    private function handleSoundGame($game, $category, $student_id)
//    {
//        $gameService = new GameStateManagerService();
//
//        // جلب الكلمات المرتبطة بملف صوتي
//        $possibleCorrectItems = Word::where('category_id', $category->id)
//            ->whereNotNull('audio_id')
//            ->with('audio')
//            ->get();
//
//        if ($possibleCorrectItems->isEmpty()) {
//            return ControllerHelper::generateResponseApi(false, 'لا توجد مفردات مرتبطة بملف صوتي في هذا القسم لبدء اللعبة.', null, 404);
//        }
//
//        // جلب أول مرحلة للعبة
//        $firstLevel = Level::query()->where('category_id', $category->id)
//            ->whereHas('games', function ($query) use ($game) {
//                $query->where('games.id', $game->id);
//            })
//            ->orderBy('level_number')
//            ->first();
//
//        if (!$firstLevel) {
//            return ControllerHelper::generateResponseApi(false, 'لا توجد مراحل مرتبطة بهذا القسم وهذه اللعبة.', null, 404);
//        }
//
//        // اختيار كلمة عشوائية تكون الإجابة الصحيحة
//        $correctItem = $possibleCorrectItems->random();
//        $correctWord = $correctItem->word;
//        $levelWords = $possibleCorrectItems->pluck('word')->shuffle()->values();
//
//        // التأكد من تحميل الصوت
//        $correctItem->loadMissing('audio');
//        if (!$correctItem->audio || empty($correctItem->audio->path)) {
//            Log::error("Audio relationship or path is missing for VocabularyItem ID: " . $correctItem->id);
//            return ControllerHelper::generateResponseApi(false, 'حدث خطأ: لم يتم العثور على ملف الصوت المرتبط.', null, 500);
//        }
//
//        $correctAudioPath = $correctItem->audio->path;
//
//        // حفظ حالة اللعبة
//        $state = [
//            'game_type' => 'صوت',
//            'level_id' => $firstLevel->id,
//            'category_id' => $category->id,
//            'remaining_words' => $levelWords->toArray(),
//            'correct_answer' => $correctWord,
//            'score' => 0
//        ];
//
//        $gameService->updateState($student_id, $firstLevel->id, $state);
//
//        $data = [
//            'game' => $game->name,
//            'category_name' => $category->name,
//            'level_name' => $firstLevel->name,
//            'audio_url' => url(Storage::url($correctAudioPath)),
//            'correct_answer' => $correctWord
//        ];
//
//        return ControllerHelper::generateResponseApi(true, 'تم تشغيل لعبة المحادثات بنجاح', $data, 200);
//    }
//    public function checkAnswer(Request $request)
//    {
//        $request->validate([
//            'level_id' => 'required|integer',
//            'answer' => 'nullable|string',
//            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
//        ]);
//
//        if (!Auth::guard('student')->check()) {
//            return ControllerHelper::generateResponseApi(false, 'المستخدم غير مسجل للدخول.', null, 401);
//        }
//
//        $studentId = Auth::guard('student')->id();
//        $levelId = $request->input('level_id');
//
//        $gameState = $this->gameStateManager->getState($studentId, $levelId);
//
//        if (!$gameState || !isset($gameState['game_type'], $gameState['current_word'], $gameState['words_remaining'])) {
//            return ControllerHelper::generateResponseApi(false, 'لم يتم العثور على لعبة نشطة أو انتهت مدة الجلسة.', null, 404);
//        }
//
//        $gameType = $gameState['game_type'];
//        $correctWord = \App\Models\Word::find($gameState['current_word'])?->word;
//        $isMatch = false;
//        $labels = [];
//
//        if (!$correctWord) {
//            return ControllerHelper::generateResponseApi(false, 'لم يتم العثور على الكلمة الصحيحة.', null, 500);
//        }
//
//        $correctWord = strtolower($correctWord);
//
//        // -------- لعبة كلمات (تتعرف على الصورة) --------
//        if ($gameType === 'كلمات') {
//            if (!$request->hasFile('image')) {
//                return ControllerHelper::generateResponseApi(false, 'يرجى إرسال صورة.', null, 400);
//            }
//
//            $imageFile = $request->file('image');
//            $imagePath = Storage::disk('public')->put('temp_images', $imageFile);
//            $fullImagePath = storage_path('app/public/' . $imagePath);
//
//            $labels = $this->imageRecognitionService->analyzeImage($fullImagePath);
//            Storage::disk('public')->delete($imagePath);
//
//            foreach ($labels as $label) {
//                if (isset($label['description']) && strtolower($label['description']) === $correctWord) {
//                    $isMatch = true;
//                    break;
//                }
//            }
//
//            // -------- لعبتي صورة وكلمات أو صوت (إجابة نصية) --------
//        } elseif (in_array($gameType, ['صورة وكلمات', 'صوت'])) {
//            $submittedAnswer = strtolower($request->input('answer'));
//            $isMatch = ($submittedAnswer === $correctWord);
//        } else {
//            return ControllerHelper::generateResponseApi(false, 'نوع اللعبة غير مدعوم حالياً.', null, 400);
//        }
//
//        // تحديث الحالة
//        if ($isMatch) {
//            $gameState['score'] += 1;
//        }
//
//        // التحقق من نهاية الكلمات
//        if (empty($gameState['remaining_word_ids'])) {
//            $this->gameStateManager->markLevelCompleted($studentId, $levelId);
//            $this->gameStateManager->clearState($studentId, $levelId);
//
//            return ControllerHelper::generateResponseApi(true, 'أحسنت! لقد أكملت هذه المرحلة 🎉', [
//                'score' => $gameState['score'],
//                'status' => 'completed'
//            ]);
//        }
//
//        // الانتقال إلى الكلمة التالية
//        $gameState['current_word_id'] = array_shift($gameState['remaining_word_ids']);
//        $this->gameStateManager->updateState($studentId, $levelId, $gameState);
//
//        $nextWord = \App\Models\Word::find($gameState['current_word_id']);
//
//        $responseData = [
//            'is_correct' => $isMatch,
//            'next_word' => $nextWord->word ?? null,
//            'score' => $gameState['score'],
//            'remaining' => count($gameState['remaining_word_ids']),
//            'game_type' => $gameType,
//        ];
//
//        if ($gameType === 'كلمات') {
//            $responseData['labels'] = $labels;
//        }
//
//        return ControllerHelper::generateResponseApi(true, 'تم التحقق من الإجابة.', $responseData);
//    }

// ****************** new code ************************

public function getLevelsForGame(Request $request,$gameId)
{
    $game = Game::findOrFail($gameId);
    if (!$game) {
        return ControllerHelper::generateResponseApi(false,'اللعبة غير موجودة',null,404);
    }
    if (!Auth::guard('student')->check()) {
        return ControllerHelper::generateResponseApi(false,'المستخدم غير مسجل للدخول',null,401);
    }
    $studentId = Auth::guard('student')->user()->id;
    $levels = Level::whereHas('games',function ($query) use ($gameId){
        $query->where('games.id',$gameId);
    })
        ->withExists(['completedByStudents' => function ($query) use ($studentId){
            $query->where('student_id',$studentId);
        }])
        ->where('is_active' ,true)
        ->orderBy('level_number')
        ->get(['id','level_number','name','description','points_reward']);
    return ControllerHelper::generateResponseApi(true,'تم جلب مراحل اللعبة بنجاح',$levels);
}

    /**
     * بدء مرحلة للعبة محددة
     * POST /api/games/{gameId}/levels/{levelId}/start
     */
    public function startLevel(Request $request, $gameId, $levelId) // استقبال كلا المعرفين
    {
        $gameId = (int) $gameId;
        $levelId = (int) $levelId;

        if (!Auth::guard('student')->check()) {
            return ControllerHelper::generateResponseApi(false, 'المستخدم غير مسجل للدخول.', null, 401);
        }
        $studentId = Auth::guard('student')->id();

        $game = Game::with('type')->find($gameId);
        if (!$game || !$game->type) {
            return ControllerHelper::generateResponseApi(false, 'اللعبة المطلوبة غير موجودة أو نوعها غير محدد.', null, 404);
        }
        $gameType = $game->type->name;

        $level = Level::with('category')
            ->where('id', $levelId)
            ->where('is_active', true)
            ->whereHas('games', function ($query) use ($gameId) {
                $query->where('games.id', $gameId);
            })
            ->first();

        if (!$level) {
            return ControllerHelper::generateResponseApi(false, "المرحلة (ID:{$levelId}) غير موجودة، غير نشطة، أو غير مرتبطة باللعبة (ID:{$gameId}).", null, 404);
        }

        if (!$level->category) {
            Log::error("Level {$levelId} is missing an associated category.");
            return ControllerHelper::generateResponseApi(false, 'خطأ في إعدادات المرحلة.', null, 500);
        }
        $categoryId = $level->category_id;

         if ($level->level_number > 1) {
             $previousLevelCompleted = DB::table('student_level')
                                     ->where('student_id', $studentId)
                                     ->whereHas('level', function ($q) use ($level) {
                                         $q->where('game_id', $level->game_id) // التأكد من أنها لنفس اللعبة
                                           ->where('level_number', $level->level_number - 1);
                                      })->exists();
             if (!$previousLevelCompleted) {
                 return ControllerHelper::generateResponseApi(false, 'يجب إكمال المستوى السابق أولاً.', null, 403);
             }
         }
        $wordIdsQuery = Word::where('category_id', $categoryId);
        if ($gameType === 'صورة وكلمات') {
            $wordIdsQuery->whereNotNull('image_id');
        } elseif ($gameType === 'صوت') {
            $wordIdsQuery->whereNotNull('audio_id');
        }
        $wordIds = $wordIdsQuery->pluck('id')->shuffle()->toArray();

        if (empty($wordIds)) {
            return ControllerHelper::generateResponseApi(false, "لا يوجد محتوى مناسب لقسم '{$level->category->name}' يتوافق مع لعبة '{$gameType}'.", null, 404);
        }

        $this->gameStateManager->startLevel($studentId, $levelId, $gameType, $wordIds);

        // 7. جلب بيانات السؤال الأول
        $currentState = $this->gameStateManager->getState($studentId, $levelId);
        if (!$currentState || !isset($currentState['current_word_id'])) {
            Log::critical("Failed to retrieve state immediately after starting level {$levelId} for student {$studentId}.");
            return ControllerHelper::generateResponseApi(false, "حدث خطأ غير متوقع عند بدء المرحلة.", null, 500);
        }
        $firstWordId = $currentState['current_word_id'];
        $firstQuestionData = $this->prepareQuestionData($firstWordId, $gameType, $level);

        if (isset($firstQuestionData['error'])) {
            $this->gameStateManager->clearState($studentId, $levelId);
            return ControllerHelper::generateResponseApi(false, "خطأ في بيانات السؤال الأول: " . $firstQuestionData['error'], null, 500);
        }

        $responseData = array_merge([
            'game_name' => $game->name,
            'level_name' => $level->name,
            'category_name' => $level->category->name,
        ], $firstQuestionData);

        return ControllerHelper::generateResponseApi(true, "المرحلة '{$level->name}' للعبة '{$game->name}' بدأت!", $responseData, 200);
    }

    public function checkAnswer(Request $request, $levelId)
    {
        $validator = Validator::make($request->all(), [
            'answer' => 'nullable|string|max:191',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);
        if ($validator->fails()) {
            return ControllerHelper::generateResponseApi(false, 'بيانات الإجابة غير صالحة.', $validator->errors(), 422);
        }
        if (!Auth::guard('student')->check()) {
            return ControllerHelper::generateResponseApi(false, 'المستخدم غير مسجل للدخول.', null, 401);
        }
        $studentId = Auth::guard('student')->id();
        $gameState = $this->gameStateManager->getState($studentId, $levelId);
        if (!$gameState || !isset($gameState['game_type'], $gameState['current_word_id'], $gameState['remaining_word_ids'])) {
            return ControllerHelper::generateResponseApi(false,
                'لم يتم العثور على لعبة نشطة لهذه المرحلة أو انتهت مدة الجلسة.', null, 404);
        }
        $gameType = $gameState['game_type'];
        $correctWordId = $gameState['current_word_id'];
        $correctWordModel = Word::find($correctWordId);
        if (!$correctWordModel) {
            $this->gameStateManager->clearState($studentId, $levelId); // تنظيف الحالة عند خطأ فادح
            Log::error("Could not find Word with ID {$correctWordId} specified in game state for student {$studentId}, level {$levelId}. State cleared.");
            return ControllerHelper::generateResponseApi(false, 'خطأ فادح: الكلمة الحالية غير موجودة.', null, 500);
        }
        $correctWordText = strtolower($correctWordModel->word);
        $isMatch = false;
        $detectedLabels = [];
        // -------- التحقق حسب نوع اللعبة --------

        if ($gameType === 'كلمات') {
            if (!$request->hasFile('image')) {
                return ControllerHelper::generateResponseApi(false, 'لعبة الكلمات تتطلب إرسال صورة.', null, 400);
            }

            $imageFile = $request->file('image');
            $imagePath = null;
            try {
                $imagePath = Storage::disk('public')->put('temp_images', $imageFile);
                if (!$imagePath) {
                    throw new \Exception("Failed to store temporary image.");
                }
                $fullImagePath = storage_path('app/public/'.$imagePath);
                $detectedLabels = $this->imageRecognitionService->analyzeImage($fullImagePath);
                if (!empty($detectedLabels)) {
                    foreach ($detectedLabels as $label) {
                        if (isset($label['description']) && strtolower($label['description']) === $correctWordText) {
                            $isMatch = true;
                            break;
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error("Image check failed for student {$studentId}, level {$levelId}: ".$e->getMessage());
                // لا نوقف اللعبة بالضرورة، لكن قد نرجع خطأ
                // return ControllerHelper::generateResponseApi(false, 'حدث خطأ أثناء تحليل الصورة.', null, 500);
                $isMatch = false;
            } finally {
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }

        } elseif (in_array($gameType, ['صورة وكلمات', 'صوت'])) {
            if (!$request->filled('answer')) {
                return ControllerHelper::generateResponseApi(false, "هذه اللعبة تتطلب إرسال إجابة نصية ('answer').",
                    null, 400);
            }
            $submittedAnswer = strtolower(trim($request->input('answer')));
            $isMatch = ($submittedAnswer === $correctWordText);

        } else {
            Log::error("Unsupported game type '{$gameType}' encountered during checkAnswer for student {$studentId}, level {$levelId}.");
            return ControllerHelper::generateResponseApi(false, 'نوع اللعبة غير مدعوم للتحقق.', null, 400);
        }
        // -------- تحديث الحالة والتقدم --------

        $currentScore = $gameState['score'];
        if ($isMatch) {
            $currentScore += 1; // زيادة النقاط لهذه الجولة
        }

        // التحقق من نهاية كلمات المرحلة
        if (empty($gameState['remaining_word_ids'])) {
            // المرحلة اكتملت
            $firstCompletion = $this->gameStateManager->markLevelCompleted($studentId, $levelId);
            $this->gameStateManager->clearState($studentId, $levelId); // تنظيف الحالة

            // جلب النقاط الكلية المحدثة
            $student = Student::find($studentId);
            $totalPoints = $student ? $student->points : 0;

            return ControllerHelper::generateResponseApi(true,
                ($isMatch ? 'إجابة صحيحة! ' : '').'لقد أكملت هذه المرحلة بنجاح 🎉', [
                    'is_correct' => $isMatch, // نتيجة المحاولة الأخيرة
                    'final_score' => $currentScore, // نتيجة المرحلة الحالية
                    'status' => 'level_completed',
                    'level_completed_first_time' => $firstCompletion,
                    'total_points' => $totalPoints, // إجمالي نقاط الطالب
                ]);

        } else {
            // الانتقال إلى الكلمة/السؤال التالي
            $nextWordId = array_shift($gameState['remaining_word_ids']);
            $newState = [
                'game_type' => $gameType,
                'level_id' => $levelId, // إعادة إضافته للتأكد
                'current_word_id' => $nextWordId,
                'remaining_word_ids' => $gameState['remaining_word_ids'], // القائمة المحدثة
                'score' => $currentScore, // السكور المحدث
            ];
            $this->gameStateManager->updateState($studentId, $levelId, $newState);

            // تحضير بيانات السؤال التالي
            // نحتاج لجلب المرحلة مرة أخرى إذا لم تكن معنا
            $level = Level::find($levelId); // قد يكون من الأفضل تخزين اسم المرحلة في الحالة أيضًا
            if (!$level) { /* معالجة الخطأ */
            }
            $nextQuestionData = $this->prepareQuestionData($nextWordId, $gameType, $level);


            if (isset($nextQuestionData['error'])) {
                $this->gameStateManager->clearState($studentId, $levelId);
                return ControllerHelper::generateResponseApi(false,
                    "خطأ في بيانات السؤال التالي: ".$nextQuestionData['error'], null, 500);
            }

            $responseData = [
                'is_correct' => $isMatch,
                'current_score' => $currentScore, // السكور حتى الآن في هذه المرحلة
                'status' => 'next_question',
                'next_question_data' => $nextQuestionData,
            ];

            // إضافة معلومات إضافية عند الخطأ (اختياري)
            if (!$isMatch) {
//            $responseData['correct_answer_text'] = $correctWordText; // مساعدة الطالب
                if ($gameType === 'كلمات' && !empty($detectedLabels)) {
                    // إرسال أهم النتائج فقط
                    $responseData['detected_labels'] = array_slice(array_column($detectedLabels, 'description'), 0, 5);
                }
            }

            return ControllerHelper::generateResponseApi(true,
                ($isMatch ? 'إجابة صحيحة! السؤال التالي...' : 'إجابة خاطئة. حاول مجدداً مع السؤال التالي...'),
                $responseData);
        }
    }
    private function prepareQuestionData(?int $wordId, string $gameType, Level $level): array
    {
        if (!$wordId) {
            Log::warning("prepareQuestionData called with null wordId for level {$level->id}");
            return ['error' => 'Invalid Word ID provided.'];
        }

        // تحميل العلاقات المطلوبة بكفاءة
        $wordModel = Word::with(['image', 'audio'])->find($wordId);

        if (!$wordModel) {
            Log::error("Could not find Word with ID {$wordId} for level {$level->id}");
            return ['error' => "Word data not found ({$wordId})."];
        }

        // تأكد من وجود القسم المرتبط بالمستوى (حسب النموذج Level -> Category)
        if (!$level->relationLoaded('category')) {
            $level->load('category');
        } // تحميل إذا لم يكن محملاً
        if (!$level->category) {
            Log::error("Category not loaded or associated with level {$level->id}");
            return ['error' => "Level category configuration error."];
        }

        $data = [
            // 'level_id' => $level->id,
            // 'level_name' => $level->name,
            'game_type' => $gameType,
            // 'correct_word' => $wordModel->word
        ];

        try {
            if ($gameType === 'كلمات') {
                $data['word_to_find'] = $wordModel->word;

            } elseif ($gameType === 'صورة وكلمات') {
                if (!$wordModel->image || empty($wordModel->image->image)) {
                    throw new \Exception("Image path missing for Word ID {$wordId}.");
                }
                $correctWord = $wordModel->word;
                $correctImagePath = $wordModel->image->image;

                $allWordsInCategory = Word::where('category_id', $level->category_id)
                    ->where('id', '!=', $wordId)
                    // ->whereNotNull('image_id')
                    ->pluck('word')
                    ->unique()
                    ->values();

                $neededIncorrect = 3;
                $availableIncorrect = $allWordsInCategory->count();

                $fetchCount = $neededIncorrect;
                if ($availableIncorrect < $neededIncorrect) {
                    Log::warning("Not enough distinct words in category {$level->category_id} to generate {$neededIncorrect} incorrect options for word ID {$wordId} ('{$correctWord}'). Available: {$availableIncorrect}");
                    $fetchCount = $availableIncorrect;
                }

                $incorrectWords = collect([]);
                if ($fetchCount > 0) {
                    $actualFetchCount = min($fetchCount, $availableIncorrect);
                    if ($actualFetchCount > 0) {
                        $incorrectWords = $allWordsInCategory->random($actualFetchCount);
                        // إذا كانت random() تُرجع عنصراً واحداً كنص (وليس collection) عندما يكون العدد 1، قم بتحويله
                        if (is_string($incorrectWords)) {
                            $incorrectWords = collect([$incorrectWords]);
                        }
                    }
                }

                $words = collect([$correctWord])->merge($incorrectWords)->shuffle();
//                dd($words);

                $data['image_url'] = url(Storage::url($correctImagePath));
                $data['options'] = $words->values()->all();

            } elseif ($gameType === 'صوت') {
                $correctWord = $wordModel->word;
                if (!$wordModel->audio || empty($wordModel->audio->path)) {
                    throw new \Exception("Audio path missing for Word ID {$wordId}.");
                }
                $data['audio_url'] = url(Storage::url($wordModel->audio->path));
                $data['correct_word'] = $correctWord;
            }
        } catch (\Exception $e) {
            Log::error("Error preparing question data for word {$wordId}, game {$gameType}, level {$level->id}: ".$e->getMessage());
            return ['error' => 'حدث خطأ أثناء تحضير بيانات السؤال.'];
        }

        return $data;
    }

}



