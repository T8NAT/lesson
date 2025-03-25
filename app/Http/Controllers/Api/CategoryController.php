<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ControllerHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::query()->with('games')->latest()->get();
        $categories_data = CategoryResource::collection($categories);
        return ControllerHelper::generateResponseApi(true, 'كافة الاقسام', $categories_data, 200);
    }

    public function category($id){
        $category = Category::query()->with('games',function ($q) use($id){
            $q->where('category_id',$id);
        })->find($id);

        return ControllerHelper::generateResponseApi(true,'تم عرض العاب القسم بنجاح', CategoryResource::make($category));

    }
}
