<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerHelper;
use App\Models\Image;
use App\Services\UploadService;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()){
            $query = Image::query()->latest();
            return datatables()->of($query)
                ->addColumn('actions', function ($row) {
                    return view('cms.image.partials.actions', compact('row'))->render();
                })
                ->addColumn('checkbox', function ($row) {
                    return '<input class="form-check-input" type="checkbox"  id="select-all"  data-kt-check-target="#kt_image_table .form-check-input" value="1" data-id="'.$row->id.'">';
                })
                ->addColumn('partials', function ($row) {
                    return view('cms.image.partials.partials', compact('row'))->render();
                })
                ->rawColumns(['actions','checkbox','partials'])
                ->make(true);

        }
        return view('cms.image.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cms.image.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,UploadService $uploadService)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['name','image']);
        $image = $uploadService->uploadImage($request,'image','images/games/images');
        $data['image'] = $image;
        $is_Saved = Image::query()->create($data);
        if($is_Saved){
            return ControllerHelper::generateResponse('success',"تم اضافة الصورة بنجاح",201);
        }else{
            return ControllerHelper::generateResponse('error',"لم يتم اضافة الصورة حاول مرة اخرى !",500);
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
        $image = Image::query()->findOrFail($id);
        return view('cms.image.edit',compact('image'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id,UploadService $uploadService)
    {
        $request->request->add(['id' => $id]);
        $request->validate([
            'id' => 'integer|exists:images,id',
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['name','image']);
        $image = $uploadService->uploadImage($request,'image','images/');
        $data['image'] = $image;
        $is_Saved = Image::query()->create($data);
        if($is_Saved){
            return ControllerHelper::generateResponse('success',"تم اضافة الصورة بنجاح",201);
        }else{
            return ControllerHelper::generateResponse('error',"لم يتم اضافة الصورة حاول مرة اخرى !",500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $image = Image::query()->findOrFail($id);
        if ($image->image){
            $imagePath = public_path('storage/'.$image->image);
            if (file_exists($imagePath)){
                unlink($imagePath);
            }
        }
        $is_Deleted = $image->delete();

        if ($is_Deleted){
            return ControllerHelper::generateResponse('success','تم حذف الصورة بنجاح');
        }else{
            return ControllerHelper::generateResponse('error','لم يتم حذف الصورة، حاول مرة اخرى',500);
        }
    }
}
