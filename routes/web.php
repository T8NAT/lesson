<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\WordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::prefix('dashboard')->middleware(['auth:user,admin'])->group(function () {
    Route::view('/', 'cms.dashboard')->name('dashboard');

    Route::resources([
        'admins'           =>   AdminController::class,
        'users'            =>   UserController::class,
        'categories'       =>   CategoryController::class,
        'roles'            =>   RoleController::class,
        'permissions'      =>   PermissionController::class,
        'settings'         =>   SettingController::class,
        'teachers'         =>   TeacherController::class,
        'games'            =>   GameController::class,
        'words'            =>   WordController::class,
        'types'            =>   TypeController::class,
        'images'           =>   ImageController::class,
        'students'         =>   StudentController::class,
        'audios'           =>   AudioController::class,
    ]);

    Route::get('get-categories',[CategoryController::class,'getCategories'])->name('getCategories');
    Route::get('get-types',[TypeController::class,'getTypes'])->name('getTypes');

    Route::put('user-update-email/{id}',[UserController::class,'updateEmail'])->name('user-update-email');
    Route::put('user-update-password/{id}',[UserController::class,'updatePassword'])->name('user-update-password');
    Route::put('user-update-role/{id}',[UserController::class,'updateRole'])->name('user-update-role');


    Route::delete('delete-selected',[UserController::class,'deleteSelected']);
    Route::get('logout',[AuthController::class,'logout'])->name('logout');

    Route::get('admin-logout',[AuthController::class,'adminLogout'])->name('admin-logout');
    Route::post('/store-media', [GameController::class, 'storeMedia'])->name('store-media');


});

Route::prefix('dashboard')->group(function (){
    Route::post('admin/login', [AuthController::class,'adminLogin'])->name('admin-login');
    Route::post('/login' , [AuthController::class,'login'])->name('login');
    Route::get('/login'  , [AuthController::class,'showLoginForm'])->name('show-login');
});

Route::get('/test-image', [\App\Http\Controllers\Api\HomeController::class, 'test_image']);

//Route::get('test',[\App\Http\Controllers\Api\HomeController::class,'test_image']);
