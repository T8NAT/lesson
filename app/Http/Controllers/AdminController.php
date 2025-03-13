<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerHelper;
use App\Models\Role;
use App\Models\User;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()){
            $admins = User::query()->with('role')->where('role_id' ,'=', 1)->latest();
            return datatables()->of($admins)
                ->addColumn('actions', function ($admin) {
                    return view('cms.users.admin.partials.actions', compact('admin'))->render();
                })
                ->addColumn('checkbox', function ($admin) {
                    return '<input class="form-check-input" type="checkbox"  id="select-all"  data-kt-check-target="#kt_ecommerce_attribute_table .form-check-input" value="1" data-id="'.$admin->id.'">';
                })
                ->addColumn('partials', function ($admin) {
                    return view ('cms.users.admin.partials.partials', compact('admin'))->render();
                })
                ->editColumn('role', function ($admin) {
                    return $admin->role->name;
                })
                ->editColumn('last_login', function ($admin) {
                    return $admin->last_login;
                })

                ->editColumn('created_at', function ($admin) {
                    return $admin->created_at->diffForHumans();
                })

                ->editColumn('status', function ($post) {
                    if ($post->status == 'active') {
                        return '<div class="badge badge-light-success">'. 'فعال' .'</div>';
                    } else{
                        return '<div class="badge badge-light-danger">'. 'غير فعال' .'</div>';
                    }
                })
                ->rawColumns(['actions', 'checkbox', 'partials', 'status'])
                ->make(true);
        }
        $roles = Role::query()->get();
        return view('cms.users.admin.index', compact('roles'));
    }


    public function create()
    {
        //
        $roles = Role::query()->get();
        return view('cms.users.admin.create', compact('roles'));
    }


    public function store(Request $request)
    {
        //
//        dd($request->all());
        $request->validate([
            'first_name' => 'required|string|min:3|max:30',
            'last_name' => 'required|string|min:3|max:30',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'address' => 'required|string|min:3|max:20',
            'phone' => 'required|string|unique:users',
            'role_id' => 'required|exists:roles,id',
            'gender' => 'required|string|in:male,female',
            'status' => 'in:active,inactive,blocked',
            'last_login' => 'nullable',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);
        $data = $request->only(['name', 'email', 'password', 'address', 'phone', 'role_id', 'gender', 'status']);


        $avatar = $this->uploadService->uploadImage($request, 'avatar', 'images/admins');
        $data['avatar'] = $avatar;

        $data['password']= Hash::make($request->password);

        $status = $request->input('status');
        $data['status'] = ($status === 'active') ? 'active' : (($status === 'inactive') ? 'inactive' : 'blocked');

        $isSaved = User::query()->create($data);
        if ($isSaved) {
            return ControllerHelper::generateResponse('success','تم اضافة مدير جديد بنجاح');
        }else{
            return ControllerHelper::generateResponse('error','يرجى تصحيح الأخطاء ثم المحاولة مرة أخرى.',500);
        }

    }

    public function show($id)
    {
        $roles = Role::query()->with('permissions')->get();
        $admin = User::query()->findOrFail($id);
        return view('cms.users.admin.edit', compact('admin', 'roles'));    }

    public function edit($id)
    {
        //
        $roles = Role::query()->get();
        $admin = User::query()->findOrFail($id);
        return view('cms.users.admin.edit', compact('admin', 'roles'));
    }

    public function update(Request $request, $id)
    {
        //
//        dd($request->all());
        $request->request->add(['id' => $id]);
        $request->validate([
            'id' => 'required|integer|exists:users,id',
            'first_name' => 'required|string|min:3|max:30,' . $id,
            'last_name' => 'required|string|min:3|max:30,' . $id,
            'email' => 'required|string|email|unique:users,email,' . $id,
            'password' => 'required|string|min:6',
            'address' => 'required|string|min:3|max:20',
            'phone' => 'required|string|unique:users,phone,' . $id,
            'role_id' => 'required|exists:roles,id',
            'gender' => 'required|string|in:male,female',
            'status' => 'in:active,inactive,blocked',
        ]);

        $data = $request->only([
            'name', 'email', 'password', 'address',
            'phone', 'role_id', 'gender',
        ]);

        $admin = User::query()->find($id);

        if ($admin){
            if ($request->hasFile('avatar')) {
                if ($admin->avatar) {
                    $imagePath =public_path('storage/'.$admin->avatar);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
                $avatar = $this->uploadService->uploadImage($request, 'avatar', 'images/admins');
                $data['avatar'] = $avatar;

            }else{
                $data['avatar'] = $admin->avatar;
            }
        }

        $status = $request->input('status');
        $data['status'] = ($status === 'active') ? 'active' : (($status === 'inactive') ? 'inactive' : 'blocked');

        $data['password'] = Hash::make($request->password);

        $isUpdated  =  $admin->update($data);

        if ($isUpdated) {
            return response()->json([
                'success' => true,
                'icon' =>'success',
                'text' => 'تم تعديل بيانات المدير بنجاح.',
            ]);
        }else{
            return response()->json([
                'error' => false,
                'icon' =>'erorr',
                'text' => 'يرجى تصحيح الأخطاء ثم المحاولة مرة أخرى.',
            ]);
        }


    }

    public function destroy($id)
    {
        //
        $admin = User::query()->find($id);
        if ($admin){
            $image = $admin->avatar ;
            if ($image){
                $imagePath = public_path('storage/' . $image);
                if (file_exists($imagePath)){
                    unlink($imagePath);
                }
            }
        }
        $isDeleted  =  $admin->delete();
        if ($isDeleted) {
            return response()->json([
                'icon' => 'success',
                'text' => 'تم حذف المدير بنجاح',
            ]);
        } else {
            return response()->json([
                'icon' => 'error',
                'text' => 'فشلت العملية! ،يرجى المحاولة مرة اخرى',
            ]);
        }
    }

}
