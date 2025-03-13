<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerHelper;
use App\Models\Word;
use Illuminate\Http\Request;

class WordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Word::query()->latest();
            return datatables()->of($query)
                ->addColumn('actions', function ($row) {
                    return view('cms.word.partials.actions', compact('row'))->render();
                })
                ->addColumn('checkbox', function ($row) {
                    return '<input class="form-check-input" type="checkbox"  id="select-all"  data-kt-check-target="#kt_word_table .form-check-input" value="1" data-id="'.$row->id.'">';
                })
                ->addColumn('words', function ($row) {
                    return $row->words;
                })
                ->addColumn('category', function ($row) {
                    return $row->category->name;
                })
                ->rawColumns(['actions', 'checkbox'])
                ->make(true);
        }
        return view('cms.word.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cms.word.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'words' => 'required|array',
        ]);

        $data = $request->only(['category_id', 'words']);
        $is_saved = Word::query()->create($data);
        if ($is_saved) {
            return ControllerHelper::generateResponse('success','تم اضافة الكلمات بنجاح');
        }else{
            return ControllerHelper::generateResponse('error','فشلت عملية الاضافة حاول مرة اخرى', 500);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
