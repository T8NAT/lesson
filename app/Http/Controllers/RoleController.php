<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::query()->with(['users','permissions'])->get();
        return view('cms.users.role.index',compact('roles'));
    }

    public function create()
    {
        return view('cms.users.role.create');

    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'description'=>'nullable|string',
          ]);
        $data = $request->only(['name']);
        $is_saved = Role::query()->create($data);
        if ($is_saved){
            return response()->json([
                'icon' => 'success',
                'text' => 'تم اضافة الدور بنجاح',
            ]);
        }else{
            return response()->json([
                'icon' => 'error',
                'text' => 'فشلت العملية ! يرجى المحاولة مرة اخرى',
            ]);
        }
    }

    public function show($id)
    {
        $role = Role::query()->with(['users','permissions'])->findOrFail($id);
        return view('cms.users.role.show',compact('role'));
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'=>'required|string',
            'description'=>'nullable|string',
        ]);

        $role = Role::query()->findOrFail($id);

        $data = $request->only(['name','description']);

        $is_Updated = $role->update($data);

        if ($is_Updated){
            return response()->json([
                'icon' => 'success',
                'text' => 'تم تعديل الدور بنجاح',
            ]);
        }else{
            return response()->json([
                'icon' => 'error',
                'text' => 'فشلت العملية ! يرجى المحاولة مرة اخرى',
            ]);
        }
    }

    public function destroy($id)
    {
        $isDeleted = Role::destroy($id);
        if ($isDeleted) {
            return response()->json([
                'icon' => 'success',
                'text' => 'تم حذف الدور بنجاح',
            ]);
        } else {
            return response()->json([
                'icon' => 'error',
                'text' => 'فشلت العملية! ،يرجى المحاولة مرة اخرى',
            ]);
        }
    }
}
