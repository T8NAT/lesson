<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerHelper;
use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function index(Request $request)
    {
        if($request->ajax()){
            $users = User::query()->with('role')->whereNot('role_id','=',1)->latest();
            return datatables()->of($users)
                ->addColumn('actions', function ($user) {
                    return view('cms.users.user.partials.actions', compact('user'))->render();
                })
                ->addColumn('checkbox', function ($user) {
                    return '<input class="form-check-input" type="checkbox"  id="select-all"  data-kt-check-target="#kt_table_users .form-check-input" value="1" data-id="'.$user->id.'"/>';
                })
                ->addColumn('partials', function ($user) {
                    return view('cms.users.user.partials.partials', compact('user'))->render();
                })
                ->editColumn('role', function ($user) {
                    return $user->role->name;
                })
                ->editColumn('last_login', function ($user) {
                    return $user->last_login;
                })

                ->editColumn('created_at', function ($user) {
                    return $user->created_at->diffForHumans();
                })
                ->editColumn('status', function ($user) {
                    if ($user->status == 'active') {
                        return '<div class="badge badge-light-success">'. 'فعال' .'</div>';
                    } else{
                        return '<div class="badge badge-light-danger">'. 'غير فعال' .'</div>';
                    }
                })
                ->rawColumns(['actions','checkbox','status','partials'])
                ->make(true);
        }
        $roles = Role::query()->get();
        return view('cms.users.user.index',compact('roles'));

    }

    public function create()
    {

    }

    public function store(UserRequest $request)
    {
       $request->validated();

        $data = $request->only([
            'first_name','last_name', 'email', 'phone', 'user_name', 'gender', 'role_id','type',
        ]);


        $data['status'] = $request->has('status') ? 'active' : 'inactive';
        $data['password'] = Hash::make($request->password);

        $avatar = $this->uploadService->uploadImage($request, 'avatar', 'images/users');
        $data['avatar'] = $avatar;

        $user = User::query()->create($data);

        if ($user){
            return ControllerHelper::generateResponse('success','تمت عملية اضافة المستخدم بنجاح',200);
        }else{
            return ControllerHelper::generateResponse('error','فشلت عملية اضافة المستخدم ! ، يرجى المحاولة مرة أخرى',500);
        }
    }

    public function show($id)
    {
        $user = User::query()->with(['role'])->findOrFail($id);
        $roles = Role::query()->get();
        return view('cms.users.user.show',compact('user','roles'));
    }

    public function edit($id)
    {
    }

    public function update(UserRequest $request, $id)
    {
        $request->request->add(['id' => $id]);
        $request->validated();

        $user = User::findOrFail($id);

        $data = $request->only([
            'first_name','last_name', 'email', 'phone_number', 'user_name', 'gender', 'role_id',
        ]);

        $data['status'] = $request->has('status') ? 'active' : 'inactive';


        if ($user) {
            if ($request->hasFile('avatar')) {
                // إذا كان هناك صورة جديدة، احذف القديمة وحديث الصورة
                if ($user->avatar) {
                    $imagePath = public_path('storage/' . $user->avatar);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
                $avatar = $this->uploadService->uploadImage($request, 'avatar', 'images/users');
                $data['avatar'] = $avatar;
            } else {
                $data['avatar'] = $user->avatar;
            }
        }


        $isUpdated = $user->update($data);

        if ($isUpdated) {
            return ControllerHelper::generateResponse('success','تم تحديث بيانات المستخدم بنجاح',200);
        } else {
            return ControllerHelper::generateResponse('error','فشلت عملية تحديث بيانات المستخدم !، يرجى المحاولة مرة اخرى.',500);
        }
    }


    public function destroy($id)
    {
        $user = User::find($id);
        if ($user){
            $avatar = $user->avatar;
            if ($avatar){
                $imagePath = public_path('storage/' . $avatar);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }
        $is_Deleted = $user->delete();

        if ($is_Deleted){
            return ControllerHelper::generateResponse('success','تمت عملية الحذف بنجاح',200);
        }else{
            return ControllerHelper::generateResponse('error','فشلت عملية الحذف ! ، يرجى المحاولة مرة اخرى',500);
        }
    }

    public function deleteSelected(Request $request)
    {
        $ids = $request->input('ids');

        if (is_array($ids)) {
            foreach ($ids as $id) {
                $user = User::query()->find($id);
                if ($user) {
                    $image = $user->avatar;
                    if ($image) {
                        $imagePath = public_path('storage/' . $image);
                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                    }
                    $user->delete();
                }
            }
            return ControllerHelper::generateResponse('success','تم حذف المستخدمين بنجاح',200);
        }
        else {
            return ControllerHelper::generateResponse('error','فشلت العملية! ،يرجى المحاولة مرة اخرى',500);
        }
    }

    public function updateEmail(Request $request,$id){

        $request->request->add(['id'=>$id]);

        $is_Updated =  User::find($id);

        $is_Updated->Update([
            'email'=>$request->email,
        ]);
        if ($is_Updated){
            return ControllerHelper::generateResponse('success','تم تغيير البريد الالكتروني بنجاح',200);
        }else{
            return ControllerHelper::generateResponse('error','فشلت العملية،حاول مرة اخرى',500);
        }
    }


    public function updatePassword(Request $request,$id){

        $request->request->add(['id'=>$id]);
        $validator = Validator::make($request->all(), [
            'password'         => ['required',Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()
            ],
            'current_password'         => ['required','current_password:user',Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()
            ],
            'confirm_password'         => ['required','confirmed:password',],

        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $is_Updated =  User::find($id);

        $is_Updated->Update([
            'password'=>$request->password,
        ]);
        if ($is_Updated){
            return ControllerHelper::generateResponse('success','تم تغيير كلمة المرور بنجاح',200);
        }else{
            return ControllerHelper::generateResponse('error','فشلت العملية، برجى المحاولة مرة اخرى',500);
        }
    }

    public function updateRole(Request $request,$id){

        $request->request->add(['id'=>$id]);
        $validator = Validator::make($request->all(), [
            'role_id'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $is_Updated =  User::find($id);

        $is_Updated->Update([
            'role_id' => $request->role_id,
        ]);
        if ($is_Updated){
            return ControllerHelper::generateResponse('success','تم تغيير الدور بنجاح',200);
        }else{
            return ControllerHelper::generateResponse('error','فشلت العملية، يرجى المحاولة مرة اخرى',500);
        }

    }
}
