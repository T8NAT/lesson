<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::query()->with('role')->get();
        $roles= Role::query()->with('users')->get();
        return view('cms.users.permission.index',compact('permissions','roles'));
    }

    public function create()
    {
        return view('cms.users.permission.create');

    }

    public function store(Request $request)
    {
        $request->validate([
           'role_id' => 'required|int|exists:roles,id',
           'name' => 'required|string',
           'permissions' => 'nullable',
        ]);

        $data = $request->only(['name','permissions','role_id']);

        $is_Saved = Permission::query()->create($data);

        if ($is_Saved) {
            return response()->json([
                'success' => true,
                'confirmButtonText' =>'حسناً',
                'icon' =>'success',
                'text' => 'تم اضافة الصلاحية بنجاح.',
            ]);
        }else{
            return response()->json([
                'error' => false,
                'confirmButtonText' =>'حسناً',
                'icon' =>'error',
                'text' => 'يرجى تصحيح الأخطاء ثم المحاولة مرة أخرى.',
            ]);
        }
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        $permission = Permission::query()->findOrFail($id);
        $roles= Role::query()->with('users')->get();
        return view('cms.users.permission.edit',compact('permission','roles'));
    }

    public function update(Request $request, $id)
    {
        $request->request->add(['id' => $id]);
        $request->validate([
            'role_id' => 'required|int|exists:roles,id',
            'name' => 'required|string',
            'permissions' => 'nullable',
        ]);

        $permission = Permission::query()->find($id);

        $data = $request->only(['name','permissions','role_id']);

        $isUpdated = $permission->update($data);

        if ($isUpdated) {
            return response()->json([
                'success' => true,
                'confirmButtonText' =>'حسناً',
                'icon' =>'success',
                'text' => 'تم تعديل الصلاحية بنجاح.',
            ]);
        }else{
            return response()->json([
                'error' => false,
                'confirmButtonText' =>'حسناً',
                'icon' =>'error',
                'text' => 'يرجى تصحيح الأخطاء ثم المحاولة مرة أخرى.',
            ]);
        }
    }

    public function destroy($id)
    {
        $isDeleted = Permission::destroy($id);
        if ($isDeleted) {
            return response()->json([
                'success' => 'success',
                'icon'=>'success',
                'text'=>'تم حذف الصلاحية بنجاح'
            ]);
        }else{
            return response()->json([
                'error' =>'error',
                'icon'=>'error',
                'text'=>'فشلت العملية حاول مرة اخرى'
            ]);

        }
    }
}
