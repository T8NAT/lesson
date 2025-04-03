<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerHelper;
use App\Models\Image;
use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
        $images = Image::query()->latest()->get();
        return view('cms.word.create', compact('images'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'items' => 'required|array|min:1',
            'items.*.word' => 'required|string|max:191',
            'items.*.image_id' => 'nullable|integer|exists:images,id',
             'items.*.audio_path' => 'nullable|string|max:255',
        ],
            [
                'category_id.required' => 'حقل القسم مطلوب.',
                'items.required' => 'يجب إضافة مفردة واحدة على الأقل.',
                'items.min' => 'يجب إضافة مفردة واحدة على الأقل.',
                'items.*.word.required' => 'حقل المفردة (الكلمة) مطلوب لكل عنصر.',
                'items.*.word.max' => 'يجب ألا يتجاوز طول المفردة 191 حرفًا.',
                'items.*.image_id.exists' => 'معرف الصورة المحدد غير صالح.',
            ]);


        if ($validator->fails()) {
            return ControllerHelper::generateResponse('error', 'خطأ في البيانات المدخلة', 422);
        }

        $categoryId = $request->input('category_id');
        $items = $request->input('items');

        DB::beginTransaction();
        try {
            foreach ($items as $itemData) {
                if (empty(trim($itemData['word']))) {
                    continue;
                }

                Word::create([
                    'category_id' => $categoryId,
                    'word' => trim($itemData['word']),
                    'image_id' => $itemData['image_id'] ?? null,
                    'audio_path' => $itemData['audio_path'] ?? null,
                ]);
            }

//            dd($request);

            DB::commit();
            return ControllerHelper::generateResponse('success', 'تمت إضافة المفردات بنجاح',201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing vocabulary items: ' . $e->getMessage());

            return ControllerHelper::generateResponse('error', 'فشلت عملية الإضافة. حدث خطأ غير متوقع.', 500);
        }
    }
//    public function store(Request $request)
//    {
//        $request->validate([
//            'category_id' => 'required|exists:categories,id',
//            'words' => 'required|array',
//        ]);
//
//        $data = $request->only(['category_id', 'words']);
//        $is_saved = Word::query()->create($data);
//        if ($is_saved) {
//            return ControllerHelper::generateResponse('success','تم اضافة الكلمات بنجاح');
//        }else{
//            return ControllerHelper::generateResponse('error','فشلت عملية الاضافة حاول مرة اخرى', 500);
//        }
//    }

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
        $word = Word::query()->findOrFail($id);
        $images = Image::query()->latest()->get();
        return view('cms.word.edit', compact('word', 'images'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->request->add(['id' => $id]);
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'items' => 'required|array|min:1',
            'items.*.word' => 'required|string|max:191',
            'items.*.image_id' => 'nullable|integer|exists:images,id',
            'items.*.audio_path' => 'nullable|string|max:255',
        ],
            [
                'category_id.required' => 'حقل القسم مطلوب.',
                'items.required' => 'يجب إضافة مفردة واحدة على الأقل.',
                'items.min' => 'يجب إضافة مفردة واحدة على الأقل.',
                'items.*.word.required' => 'حقل المفردة (الكلمة) مطلوب لكل عنصر.',
                'items.*.word.max' => 'يجب ألا يتجاوز طول المفردة 191 حرفًا.',
                'items.*.image_id.exists' => 'معرف الصورة المحدد غير صالح.',
            ]);


        if ($validator->fails()) {
            return ControllerHelper::generateResponse('error', 'خطأ في البيانات المدخلة', 422);
        }

        $categoryId = $request->input('category_id');
        $items = $request->input('items');

        DB::beginTransaction();
        try {
            foreach ($items as $itemData) {
                if (empty(trim($itemData['word']))) {
                    continue;
                }

                Word::update([
                    'category_id' => $categoryId,
                    'word' => trim($itemData['word']),
                    'image_id' => $itemData['image_id'] ?? null,
                    'audio_path' => $itemData['audio_path'] ?? null,
                ]);
            }

//            dd($request);

            DB::commit();
            return ControllerHelper::generateResponse('success', 'تم تعديل المفردات بنجاح',201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing vocabulary items: ' . $e->getMessage());

            return ControllerHelper::generateResponse('error', 'فشلت عملية الإضافة. حدث خطأ غير متوقع.', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $word = Word::query()->find($id);
        $is_Deleted = $word->delete();
        if ($is_Deleted){
            return ControllerHelper::generateResponse('success','تم حذف الكلمة بنجاح',201);
        }else{
            return ControllerHelper::generateResponse('error','فشلت عملية الحذف، حاول مرة اخرى',500);

        }
    }
}
