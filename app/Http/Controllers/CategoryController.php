<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerHelper;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()){
            $query = Category::query()->latest();
            return datatables()->of($query)
                ->addColumn('actions', function ($row) {
                    return view('cms.category.partials.actions', compact('row'))->render();
                })
                ->addColumn('checkbox', function ($row) {
                    return '<input class="form-check-input" type="checkbox"  id="select-all"  data-kt-check-target="#kt_ecommerce_category_table .form-check-input" value="1" data-id="'.$row->id.'">';
                })
                ->addColumn('partials', function ($row) {
                    return view('cms.category.partials.partials', compact('row'))->render();
                })
                ->editColumn('name',function($row){
                    return $row->name ?? '';
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        return '<div class="badge badge-light-success">'. 'فعال'.'</div>';
                    } else {
                        return '<div class="badge badge-light-danger">'. 'غير فعال' .'</div>';
                    }
                })

                ->rawColumns(['actions','checkbox','partials','status'])
                ->make(true);
        }

        return view('cms.category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('cms.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $request->validated();
        $data = $request->only(['name', 'slug', 'description','status']);
        $thumbnail = $this->uploadService->uploadImage($request, 'icon', 'images/categories');
        $data['icon'] = $thumbnail;

        $data['status'] = $request->input('status') == 'active' ? 'active' : 'inactive';
        $isSaved = Category::query()->create($data);

        if ($isSaved) {
            return ControllerHelper::generateResponse('success','تم اضافة القسم بنجاح.',200);
        }else{
            return ControllerHelper::generateResponse('error','يرجى تصحيح الأخطاء ثم المحاولة مرة أخرى.',500);
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
    public function edit(Request $request ,string $id)
    {

        if ($request->ajax()) {
            $page = $request->get('page', 1);
            $categories = Category::query()->with('parent')->paginate(10, ['*'], 'page', $page);
            return response()->json($categories);
        }
        $category = Category::query()->findOrFail($id);
        return view('cms.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        $request->request->add(['id' => $id]);

        $request->validated();

        $category = Category::query()->find($id);

        $data = $request->only(['name', 'slug', 'description', 'status']);

        if ($category){
            if ($request->hasFile('icon')) {
                if ($category->icon) {
                    $imagePath =public_path('storage/'.$category->icon);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
                $icon = $this->uploadService->uploadImage($request, 'icon', 'images/categories');
                $data['icon'] = $icon;

            }else{
                $data['icon'] = $category->icon;
            }
        }
        $data['status'] = $request->input('status') == 'active' ? 'active' : 'inactive';

        $isUpdated = $category->update($data);

        if ($isUpdated) {
            return ControllerHelper::generateResponse('success','تم تعديل القسم بنجاح.',200);

        }else{
            return ControllerHelper::generateResponse('error','يرجى تصحيح الأخطاء ثم المحاولة مرة أخرى.',500);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::query()->findOrFail($id);

        if ($category){
            if ($category->icon) {
                $imagePath = public_path('storage/'.$category->icon);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }
        $isDeleted = $category->delete();
        if ($isDeleted) {
            return ControllerHelper::generateResponse('success','تم حذف القسم بنجاح.',200);

        }else{
            return ControllerHelper::generateResponse('error','حدث خطأ ما، فشلت عملية الحذف!',500);
        }
    }

    public function getCategories(Request $request)
    {
        $page = $request->get('page', 1);
        $categories = Category::latest()->paginate(10, ['*'], 'page', $page);

        return response()->json($categories);

    }
}
