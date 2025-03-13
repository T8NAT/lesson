<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ControllerHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService){
        $this->userService = $userService;
    }
    public function login(Request $request)
    {
        $roles = [
            'email' => 'required|email|string|exists:users',
            'password' => 'required|',
        ];
        $validator = Validator::make($request->all(), $roles);
        if (!$validator->fails()) {
            $user = User::query()->where('email', $request->get('email'))->first();
            if (Hash::check($request->get('password'),$user->password)) {
                dd($user);
                //                     $this->revokePreviousToken($user->id);
                if ($this->checkActiveTokens($user->id)){
                    return ControllerHelper::generateResponseApi(false, 'تم رفض تسجيل الدخول، يوجد دخول نشط!');
                }else{
//                    $guard = $user->role == 'admin' ? 'admin' : ($user->role == 'teacher' ? 'teacher' : "student");
//                    Auth::guard($guard)->login($user);
                    return $this->userService->generateToken($user,'تم تسجيل الدخول بنجاح');
                }

            } else {
                return ControllerHelper::generateResponseApi(false, 'خطأ في البريد الالكتروني او كلمة المرور');
            }
        }else{
            return ControllerHelper::generateResponseApi(false,$validator->getMessageBag()->first());
        }
    }

    public function register(UserRegisterRequest $request){

        try {
          $validator =   Validator::make($request->all(), []);
            if (!$validator->fails()) {
                $data = $request->only(['first_name','last_name','email','phone','terms_and_conditions','role_id']);
                $data['password'] = Hash::make($request->password);
                $user = $this->userService->createUser($data);
                if ($user){
                   return $this->userService->generateToken($user,'تم التسجيل بنجاح');
                }
            }else{
                return ControllerHelper::generateResponseApi(false,$validator->getMessageBag()->first(),422);

            }

        }catch (\Exception $exception){
            return response()->json([
                'success' => false,
                'message' => 'فشل التسجيل',
                'error' => $exception->getMessage()
            ]);
        }
    }

    public function logout(Request $request)
    {
        $request->user('user')->token()->revoke();
        return ControllerHelper::generateResponseApi(true,'تم تسجيل الخروج بنجاح');
    }
    private function checkActiveTokens($userId){
        return DB::table('oauth_access_tokens')
            ->where('user_id',$userId)
            ->where('revoked',false)
            ->exists();
    }



    private function revokePreviousToken($userId, $message = null)
    {
        DB::table('oauth_access_tokens')
            ->where('user_id',$userId)
            ->update(['revoked' => true]);
    }

}
