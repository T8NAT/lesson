<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ControllerHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StudentRegisterRequest;
use App\Http\Requests\TeacherLoginRequest;
use App\Http\Requests\TeacherRegisterRequest;
use App\Models\Teacher;
use App\Services\Teacher\TeacherService;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class TeacherAuthController extends Controller
{
    protected $teacherService;
    protected $uploadService;
    public function __construct(TeacherService $teacherService ,UploadService $uploadService){
        $this->teacherService = $teacherService;
        $this->uploadService = $uploadService;
    }
    public function login(TeacherLoginRequest $request)
    {
        $validator = Validator::make($request->all(),$request->rules());
        if (!$validator->fails()) {
            $teacher = Teacher::query()->where('email', $request->get('email'))->first();
            if (Hash::check($request->get('password'),$teacher->password)) {
                if ($this->checkActiveTokens($teacher->id)){
                    return ControllerHelper::generateResponseApi(false, 'تم رفض تسجيل الدخول، يوجد دخول نشط!');
                }else{
                    return ControllerHelper::generateToken($teacher,'تم تسجيل الدخول بنجاح');
                }

            } else {
                return ControllerHelper::generateResponseApi(false, 'خطأ في البريد الالكتروني او كلمة المرور');
            }
        }else{
            return ControllerHelper::generateResponseApi(false,$validator->getMessageBag()->first());
        }
    }

    public function register(TeacherRegisterRequest $request){

        try {
            $validator =   Validator::make($request->all(), $request->rules());
            if (!$validator->fails()) {
                $data = $request->only(['first_name','last_name','email','phone','terms_and_conditions','role_id','academic_certificate','age','experience','available_times','about','contact_method']);
                $data['password'] = Hash::make($request->password);

                $academic_certificate = $this->uploadService->uploadFiles($request, 'academic_certificate', 'files/academic');
                $experience = $this->uploadService->uploadFiles($request, 'experience', 'files/experience');

                if ($academic_certificate) {
                    $data['academic_certificate'] = json_encode($academic_certificate);
                }

                if ($experience) {
                    $data['experience'] = json_encode($experience);
                }

                $teacher = $this->teacherService->createTeacher($data);

                if ($teacher){
                    return ControllerHelper::generateToken($teacher,'تم التسجيل بنجاح');
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
        $request->user('teacher')->token()->revoke();
        return ControllerHelper::generateResponseApi(true,'تم تسجيل الخروج بنجاح');
    }
    private function checkActiveTokens($teacherId){
        return DB::table('oauth_access_tokens')
            ->where('user_id',$teacherId)
            ->where('revoked',false)
            ->exists();
    }



    private function revokePreviousToken($teacherId, $message = null)
    {
        DB::table('oauth_access_tokens')
            ->where('user_id',$teacherId)
            ->update(['revoked' => true]);
    }

}
