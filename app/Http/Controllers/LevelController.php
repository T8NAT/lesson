<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerHelper;
use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Level::query()->with('games','category')->latest();
            return datatables()->of($data)
                ->addColumn('actions', function ($row) {
                    return view('cms.level.partials.actions', compact('row'))->render();
                })
                ->addColumn('checkbox', function ($row) {
                    return '<input class="form-check-input" type="checkbox"  id="select-all"  data-kt-check-target="#kt_level_table .form-check-input" value="1" data-id="'.$row->id.'">';
                })
                ->editColumn('name', function ($row) {
                    return $row->name;
                })
                ->editColumn('games', function ($row) {
                    return $row->games->pluck('name')->implode(', ');
                })
                ->editColumn('level_number', function ($row) {
                    return $row->level_number;
                })
                ->editColumn('is_active', function ($row) {
                    if ($row->is_active == 1) {
                        return '<div class="badge badge-light-success">'. 'فعالة'.'</div>';
                    } else {
                        return '<div class="badge badge-light-danger">'. 'غير فعالة' .'</div>';
                    }
                })
                ->rawColumns(['actions','checkbox','is_active'])
                ->make(true);
        }
        return view('cms.level.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cms.level.create',);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//        dd($request);
        $request->validate([
            'name' => 'required|string|max:255',
            'game_id' => 'required|exists:games,id',
            'category_id' => 'required|exists:categories,id',
            'level_number' => 'required|numeric|max:255',
            'is_active' => 'in:on',
            'description' => 'nullable|string|max:255',
            'points_reward' => 'nullable|numeric|max:255',
        ]);

       $data = $request->only(['name', 'level_number', 'description', 'category_id','points_reward']);

       $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $is_Saved = Level::query()->create($data);
        if ($request->has('game_id')) {
            $is_Saved->games()->attach($request->game_id);
        }
       if ($is_Saved) {
           return ControllerHelper::generateResponse('success','تم اضافة المرحلة بنجاح');
       }else{
           return ControllerHelper::generateResponse('error','فشلت العملية يرجى المحاولة لاحقاً',500);
       }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $level = Level::query()->findOrFail($id);
        return view('cms.level.edit', compact('level'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->request->add(['id' => $id]);
        $request->validate([
            'name' => 'required|string|max:255',
            'game_id' => 'required|exists:games,id',
            'level_number' => 'required|numeric|max:255',
            'is_active' => 'in:on',
            'description' => 'nullable|string|max:255',
            'points_reward' => 'nullable|numeric|max:255',

        ]);
        $level = Level::query()->findOrFail($id);

        $data = $request->only(['name', 'level_number', 'is_active', 'description', 'points_reward']);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $is_Updated = $level->update($data);

        if ($request->has('game_id')) {
            $level->games()->sync($request->game_id);
        }

        if ($is_Updated) {
            return ControllerHelper::generateResponse('success','تم تعديل المرحلة بنجاح');
        }else{
            return ControllerHelper::generateResponse('error','فشلت العملية يرجى المحاولة لاحقاً !',500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $is_Deleted = Level::query()->findOrFail($id);

        $is_Deleted->delete();

        if ($is_Deleted) {
            return ControllerHelper::generateResponse('success','تم حذف المرحلة بنجاح');
        }else{
            return ControllerHelper::generateResponse('error','فشلت عمليىة الحذف ، حاول مرة اخرى !',500);
        }
    }
}
