<?php
namespace App\Http\Controllers\Api;

use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * تسجيل الدخول والتحقق من البريد الإلكتروني وكلمة المرور.
     */
    public function login(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // إذا فشل التحقق، إرجاع رسالة الخطأ
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        // البحث عن المعلم في قاعدة البيانات
        $teacher = Teacher::where('email', $request->email)->first();

        // التحقق من كلمة المرور
        if (!$teacher || !Hash::check($request->password, $teacher->password)) {
            return response()->json([
                'success' => false,
                'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة'
            ], 401);
        }

        // إنشاء التوكن للمُعلم
        $token = $teacher->createToken('YourAppName')->accessToken;

        // إرجاع الاستجابة
        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الدخول بنجاح',
            'data' => [
                'teacher' => $teacher,
                'token' => $token
            ]
        ]);
    }
}
