<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ControllerHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Traits\AuthTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    use AuthTrait;

    public function showLoginForm()
    {
        return view('cms.auth.user.sign-in');
    }

    public function adminLogin(LoginRequest $request)
    {
        $request->validated();
        $credentials = $request->only('email', 'password');
        if (Auth::guard('user')->attempt($credentials)) {
            $admin = Auth::guard('user')->user();
            if ($admin->role_id == 1 && $admin->status == 'active') {
                $admin->update(['last_login'=>date('Y-m-d H:i:s')]);
                return ControllerHelper::generateResponse('success','مرحبا بك ، تم تسجيل دخولك بنجاح');
            }elseif ($admin->status == 'inactive') {
                $request->session()->invalidate();
                return ControllerHelper::generateResponse('warning','عذراً ! هذا المدير غير فعال، قم بالتواصل مع الدعم الفني',403);

            }
        } else{
            $request->session()->invalidate();
            return ControllerHelper::generateResponse('error','خطأ في البريد الالكتروني او كلمة المرور !',401);
        }
    }
    public function login(LoginRequest $request)
    {
//        dd($request);
        $request->validated();
        $credentials = $request->only('email', 'password');
        if (Auth::guard($this->checkGuard($request))->attempt($credentials)) {
            return $this->redirect($request);
        }else{
            return ControllerHelper::generateResponse(false,'بيانات الاعتماد خاطئة!');
        }
    }


    public function logout(Request $request,$type){
        Auth::guard($type)->logout();
        $request->session()->invalidate();
        return redirect()->route('show-login',$type);
    }

    public function adminLogout(Request $request){
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        return redirect()->guest(route('show-login'));
    }
}
