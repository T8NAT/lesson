<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerHelper;
use App\Http\Requests\SettingRequest;
use App\Models\Setting;
use App\Services\UploadService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $uploadImageService;
    public function __construct(UploadService $uploadImageService)
    {
        $this->uploadImageService = $uploadImageService;

    }
    public function index()
    {
        $settings = Setting::query()->first();
        return view('cms.settings.index', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cms.settings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SettingRequest $request)
    {
        $request->validated();

        $data = $request->only([
            'name', 'logo', 'phone', 'email', 'linkedin', 'url', 'facebook',
            'instagram', 'x'  , 'about' , 'favicon' ,
        ]);

        if ($request->logo) {
            $logo = $this->uploadImageService->uploadImage($request, 'logo','images/settings/logo');
            $data['logo'] = $logo;
        }
        if($request->favicon){
            $favicon = $this->uploadImageService->uploadImage($request, 'favicon','images/settings/favicon');
            $data['favicon'] = $favicon;
        }

        $is_Saved = Setting::query()->create($data);

        if ($is_Saved) {
            return ControllerHelper::generateResponse('success','تم ضبط الاعدادات ',200);
        }else{
            return ControllerHelper::generateResponse('error','حدث خطأ ما يرجى المحاولة لاحقاً !',400);

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
        $setting = Setting::query()->findOrFail($id);
        return view('cms.settings.edit', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SettingRequest $request, string $id)
    {
        $request->validated();

        $data = $request->only([
            'name', 'phone','logo', 'favicon', 'email', 'linkedin', 'url', 'facebook',
            'instagram', 'x'  , 'about'   ,
        ]);

        $setting = Setting::query()->findOrFail($id);

        if ($request->has('logo')) {
            if ($request->hasFile('logo')) {
                if ($setting->logo) {
                    $logoPath = public_path('storage/'.$setting->logo);
                    if (file_exists($logoPath)) {
                        unlink($logoPath);
                    }
                }
                $logo = $this->uploadImageService->uploadImage($request, 'logo','images/settings/logo');
                $data['logo'] = $logo;
            }else{
                $data['logo'] = $setting->logo;
            }

        }
        if($request->has('favicon')){
            if ($request->hasFile('favicon')) {
                if ($setting->favicon) {
                    $faviconPath = public_path('storage/'.$setting->favicon);
                    if (file_exists($faviconPath)) {
                        unlink($faviconPath);
                    }
                }
                $favicon = $this->uploadImageService->uploadImage($request, 'favicon','images/settings/favicon');
                $data['favicon'] = $favicon;
            }else{
                $data['favicon'] = $setting->favicon;
        }
        }


         $is_Updated = $setting->update($data);

        if ($is_Updated) {
            return ControllerHelper::generateResponse('success','تم ضبط اعدادات الموقع الالكتروني',200);
        }else{
            return ControllerHelper::generateResponse('error','حدث خطأ ما يرجى المحاولة لاحقاً !',400);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $setting = Setting::query()->findOrFail($id);
        if ($setting){
            if ($setting->logo){
                $logoPath = public_path('storage/'.$setting->logo);
                if (file_exists($logoPath)) {
                    unlink($logoPath);
                }
            }elseif ($setting->favicon){
                $faviconPath = public_path('storage/'.$setting->favicon);
                if (file_exists($faviconPath)) {
                    unlink($faviconPath);
                }
            }
        }
        $is_Deleted = $setting->delete();
        if ($is_Deleted) {
            return ControllerHelper::generateResponse('success','تم حذف الاعدادات ',200);
        }else{
            return ControllerHelper::generateResponse('error','فشلت عملية الحذف ، حدث خطأ ما ، يرجى المحاولة لاحقاً !',400);
        }
    }
}
