<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerHelper;
use App\Models\Image;
use App\Models\Word;
use App\Services\UploadService;
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
            $query = Word::query()->with('category')->latest('words.created_at');
            return datatables()->of($query)
                ->addColumn('actions', function ($row) {
                    return view('cms.word.partials.actions', compact('row'))->render();
                })
                ->addColumn('checkbox', function ($row) {
                    return '<input class="form-check-input" type="checkbox"  id="select-all"  data-kt-check-target="#kt_word_table .form-check-input" value="1" data-id="'.$row->id.'">';
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

    public function store(Request $request,UploadService $uploadService)
    {
       $request->validate([
            'category_id' => 'required|exists:categories,id',
            'items' => 'required|array|min:1',
            'items.*.word' => 'required|string|max:191',
            'items.*.image_id' => 'nullable|integer|exists:images,id',
            'items.*.audio' => [
                'nullable',
                'file',
                'mimes:mp3,wav,ogg,m4a',
                'max:2024',
            ],
        ],
            [
                'category_id.required' => 'حقل القسم مطلوب.',
                'items.required' => 'يجب إضافة مفردة واحدة على الأقل.',
                'items.min' => 'يجب إضافة مفردة واحدة على الأقل.',
                'items.*.word.required' => 'حقل المفردة (الكلمة) مطلوب لكل عنصر.',
                'items.*.word.max' => 'يجب ألا يتجاوز طول المفردة 191 حرفًا.',
                'items.*.image_id.exists' => 'معرف الصورة المحدد غير صالح.',
                'items.*.audio.file' => 'يجب أن يكون حقل الصوت ملفًا.',
                'items.*.audio.mimes' => 'نوع ملف الصوت غير مدعوم (مسموح: mp3, wav, ogg, m4a).',
                'items.*.audio.max' => 'يجب ألا يتجاوز حجم ملف الصوت 2 ميجابايت.',
            ]);

        $categoryId = $request->input('category_id');
        $items = $request->input('items');

        DB::beginTransaction();
        try {
            foreach ($items as $index=>$itemData) {
                if (empty(trim($itemData['word']))) {
                    continue;
                }
                $audioPath = null;

                $audioInputName = "items.{$index}.audio";

                if ($request->hasFile($audioInputName) && $request->file($audioInputName)->isValid()) {
                    $audioFile = $request->file($audioInputName);

                    $storagePath = 'audio/words';

                    $audioPath = $audioFile->store($storagePath, 'public');

                    Log::info("Stored audio for item index {$index} at path: {$audioPath}");

                    if (!$audioPath) {
                        throw new \Exception("Failed to store audio file for item at index {$index}.");
                    }
                } elseif ($request->hasFile($audioInputName) && !$request->file($audioInputName)->isValid()) {
                    Log::error("Invalid audio file uploaded for item at index {$index}. Error code: " . $request->file($audioInputName)->getError());

                }

                Word::create([
                    'category_id' => $categoryId,
                    'word' => trim($itemData['word']),
                    'image_id' => $itemData['image_id'] ?? null,
                    'audio' => $audioPath,
                ]);
            }


//            dd($request);

            DB::commit();
            return ControllerHelper::generateResponse('success', 'تمت إضافة المفردات بنجاح',201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing vocabulary items: ' . $e->getMessage());
            Log::debug('Request data during error: ', $request->except(['items.*.audio'])); 

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
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'items' => 'required|array|min:1',
            'items.*.word' => 'required|string|max:191',
            'items.*.image_id' => 'nullable|integer|exists:images,id',
            'items.audio' => 'nullable|string|max:255',
        ],
            [
                'category_id.required' => 'حقل القسم مطلوب.',
                'items.required' => 'يجب إضافة مفردة واحدة على الأقل.',
                'items.min' => 'يجب إضافة مفردة واحدة على الأقل.',
                'items.*.word.required' => 'حقل المفردة (الكلمة) مطلوب لكل عنصر.',
                'items.*.word.max' => 'يجب ألا يتجاوز طول المفردة 191 حرفًا.',
                'items.*.image_id.exists' => 'معرف الصورة المحدد غير صالح.',
            ]);


        $categoryId = $request->input('category_id');
        $items = $request->input('items');

        $word = Word::query()->findOrFail($id);
        DB::beginTransaction();
        try {
            foreach ($items as $itemData) {
                if (empty(trim($itemData['word']))) {
                    continue;
                }

                $word->update([
                    'category_id' => $categoryId,
                    'word' => trim($itemData['word']),
                    'image_id' => $itemData['image_id'] ?? null,
                    'audio' => $itemData['audio'] ?? null,
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
