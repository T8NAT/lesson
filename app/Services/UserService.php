<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct()
    {
    }
    public function login($email, $password)
    {

    }
    public function createUser($data)
    {
        return User::query()->create($data);
    }

    public function generateToken($user, $message = null){

        $tokenResult = $user->createToken('Lesson-User');
        $token = $tokenResult->accessToken;
        $user->setAttribute('token',$token);
        return response()->json([
            'status'=>true,
            'message'=>$message,
            'object'=>['user' => UserResource::make($user)],
        ]);
    }


}
