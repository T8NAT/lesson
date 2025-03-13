<?php

use App\Http\Controllers\Api\Auth\StudentAuthController;
use App\Http\Controllers\Api\Auth\TeacherAuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\TestGameController;
use Illuminate\Support\Facades\Route;


Route::namespace('api/')->prefix('student/')->group(function (){
    Route::post('register',[StudentAuthController::class, 'register']);
    Route::post('login',[StudentAuthController::class, 'login']);
    Route::post('logout',[StudentAuthController::class, 'logout'])->middleware('auth:student');
});


Route::namespace('api/')->prefix('teacher/')->group(function (){
    Route::post('register',[TeacherAuthController::class, 'register']);
    Route::post('login',[TeacherAuthController::class, 'login']);
    Route::post('logout',[TeacherAuthController::class, 'logout'])->middleware('auth:teacher');
});

Route::namespace('api/')->prefix('games/')->group(function (){
    Route::post('game/{id}/send', [TestGameController::class, 'sendWord']);

    Route::post('check-image/{gameId}', [GameController::class, 'checkImage'])->middleware('auth:student');
    Route::post('check-game', [GameController::class, 'checkGame'])->middleware('auth:student');
});

Route::namespace('api/')->prefix('categories/')->group(function (){
    Route::get('/get-categories', [CategoryController::class, 'index']);
    Route::get('show-category/{id}/', [CategoryController::class, 'category']);
});

