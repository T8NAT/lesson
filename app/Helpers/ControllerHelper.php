<?php
namespace App\Helpers;

use App\Http\Resources\StudentResource;
use App\Http\Resources\TeacherResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class ControllerHelper{
    public static function generateResponseApi($status, $message,$data = null,$statusCode = 200){
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ],$statusCode);
    }

    public static function generateResponse($icon,$text,$status = 200)
    {
        return response()->json([
            'icon' => $icon,
            'text' => $text,
        ],$status);

    }

    public static function generateToken(Model $user, string $message = null): JsonResponse
    {

        if ($user instanceof \App\Models\Student) {
            $resource = StudentResource::make($user);
            $tokenName = 'student-token';
        } elseif ($user instanceof \App\Models\Teacher) {
            $resource = TeacherResource::make($user);
            $tokenName = 'teacher-token';
        } else {
            return response()->json([
                'status' => false,
                'message' => 'نوع المستخدم غير مدعوم.',
                'data' => null,
            ], 400);
        }

        $tokenResult = $user->createToken($tokenName);
        $token = $tokenResult->accessToken;
        $user->setAttribute('token', $token);

        return response()->json([
            'status' => true,
            'message' => $message ?? 'تم تسجيل الدخول بنجاح',
            'data' => ['user' => $resource],
        ]);
    }
}

