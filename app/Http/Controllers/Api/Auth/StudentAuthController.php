<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ControllerHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StudentLoginRequest;
use App\Http\Requests\StudentRegisterRequest;
use App\Models\Student;
use App\Services\Student\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class StudentAuthController extends Controller
{
    protected $studentService;
    public function __construct(StudentService $studentService){
        $this->studentService = $studentService;
    }
    public function login(StudentLoginRequest $request)
    {
        $validator = Validator::make($request->all(),$request->rules());
        if (!$validator->fails()) {
            $student = Student::query()->where('email', $request->get('email'))->first();
            if (Hash::check($request->get('password'),$student->password)) {
                if ($this->checkActiveTokens($student->id)){
                    $this->revokePreviousToken($student->id);
                    return ControllerHelper::generateResponseApi(false, 'يوجد دخول نشط!، تم تسجيل الخروج من كافة الجلسات،قم بتسجيل الدخول مرة اخرى');
                }else{
                    $student->update(['last_login'=>date('Y-m-d H:i:s')]);
                    return ControllerHelper::generateToken($student,'تم تسجيل الدخول بنجاح');
                }

            } else {
                return ControllerHelper::generateResponseApi(false, 'خطأ في البريد الالكتروني او كلمة المرور');
            }
        }else{
            return ControllerHelper::generateResponseApi(false,$validator->getMessageBag()->first());
        }
    }

    public function register(StudentRegisterRequest $request){

        try {
            $validator =   Validator::make($request->all(), $request->rules());
            if (!$validator->fails()) {
                $data = $request->only(['first_name','last_name','email','phone','terms_and_conditions','role_id']);
                $data['password'] = Hash::make($request->password);
                $student = $this->studentService->createStudent($data);
                if ($student){
                    return ControllerHelper::generateToken($student,'تم التسجيل بنجاح');
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
        $request->user('student')->token()->revoke();
        return ControllerHelper::generateResponseApi(true,'تم تسجيل الخروج بنجاح');
    }
    private function checkActiveTokens($studentId){
        return DB::table('oauth_access_tokens')
            ->where('user_id',$studentId)
            ->where('revoked',false)
            ->exists();
    }



    private function revokePreviousToken($studentId, $message = null)
    {
        DB::table('oauth_access_tokens')
            ->where('user_id',$studentId)
            ->update(['revoked' => true]);
    }
}
