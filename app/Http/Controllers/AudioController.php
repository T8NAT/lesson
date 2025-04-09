<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerHelper;
use App\Models\Audio;
use App\Services\UploadService;
use Illuminate\Http\Request;

class AudioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Audio::query()->latest()->get();
            return datatables()->of($data)
                ->addColumn('actions', function ($row) {
                    return view('cms.audio.partials.actions', compact('row'))->render();
                })
                ->addColumn('checkbox', function ($row) {
                    return '<input class="form-check-input" type="checkbox"  id="select-all"  data-kt-check-target="#kt_audio_table .form-check-input" value="1" data-id="'.$row->id.'">';
                })
                ->editColumn('description', function ($row) {
                    return $row->description ?? 'لا يوجد وصف';
                })
                        ->rawColumns(['actions','checkbox','partials'])
                        ->make(true);
        }
        return view('cms.audio.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cms.audio.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,UploadService $uploadService)
    {
        $request->validate([
            'name' => 'required',
            'path' => 'required|file|mimes:mp3,wav,ogg,m4a|max:2048',
        ]);

        $data = $request->only(['name','path','description']);
        $audio = $uploadService->uploadImage($request,'path','audio/games/');
        $data['path'] = $audio;
        $is_Saved = Audio::query()->create($data);
        if($is_Saved){
            return ControllerHelper::generateResponse('success',"تم اضافة الصوت بنجاح",201);
        }else{
            return ControllerHelper::generateResponse('error',"لم يتم اضافة الصوت حاول مرة اخرى !",500);
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
        $audio = Audio::query()->findOrFail($id);
        return view('cms.audio.edit',compact('audio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id,UploadService $uploadService)
    {
        $request->request->add(['id' => $id]);
        $request->validate([
            'id'=>'required|exists:audios,id',
            'name' => 'required',
            'path' => 'required|file|mimes:mp3,wav,ogg,m4a|max:2048',
        ]);
        $audio = Audio::query()->findOrFail($id);

        $data = $request->only(['name','path']);
        if ($audio){
            if ($request->hasFile('path')) {
                if ($audio->path) {
                    $audio_path = public_path('storage/'.$audio->path);
                    if (file_exists($audio_path)) {
                        unlink($audio_path);
                    }
                }
                $audio_file = $uploadService->uploadImage($request,'path','audio/games/');
                $data['path'] = $audio_file;
            }else{
                $data['path'] = $audio->path;
            }
        }

        $is_Updated = $audio->update($data);

        if($is_Updated){
            return ControllerHelper::generateResponse('success',"تم تعديل الصوت بنجاح",201);
        }else{
            return ControllerHelper::generateResponse('error',"لم يتم اضافة الصوت حاول مرة اخرى !",500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $audio = Audio::query()->findOrFail($id);
        if ($audio->path){
            $audioPath = public_path('storage/'.$audio->path);
            if (file_exists($audioPath)){
                unlink($audioPath);
            }
        }
        $is_Deleted = $audio->delete();
        if($is_Deleted){
            return ControllerHelper::generateResponse('success','تم حذف الصوت بنجاح');
        }else{
            return ControllerHelper::generateResponse('error','فشلت عملية الحذف يرجى المحاولة مرة اخرى');
        }
    }
}
