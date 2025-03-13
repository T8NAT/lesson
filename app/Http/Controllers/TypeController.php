<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerHelper;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()){
            $query = Type::query()->latest();
            return datatables()->of($query)
                ->addColumn('actions', function ($row) {
                    return view('cms.category.partials.actions', compact('row'))->render();
                })
                ->addColumn('checkbox', function ($row) {
                    return '<input class="form-check-input" type="checkbox"  id="select-all"  data-kt-check-target="#kt_ecommerce_category_table .form-check-input" value="1" data-id="'.$row->id.'">';
                })
                ->editColumn('name',function($row){
                    return $row->name ?? '';
                })

                ->rawColumns(['actions','checkbox'])
                ->make(true);
        }
        return view('cms.type.index');
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $data = $request->only('name');
        $isSaved = Type::create($data);
        if ($isSaved) {
            return ControllerHelper::generateResponse('success','تم انشاء النوع بنجاح');
        }else{
            return ControllerHelper::generateResponse('false','فشلت عملية الاضافة حاول مرة اخرى !',500);

        }
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }

    public function getTypes(Request $request)
    {
        $page = $request->get('page', 1);
        $types = Type::latest()->paginate(10, ['*'], 'page', $page);

        return response()->json($types);

    }
}
