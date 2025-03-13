<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerHelper;
use App\Models\Game;
use App\Services\UploadService;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $uploadService;
    public function __construct(UploadService $uploadService){

        $this->uploadService = $uploadService;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Game::query()->latest();
            return datatables()->of($query)
                ->addColumn('actions', function ($row) {
                    return view('cms.game.partials.actions', compact('row'))->render();
                })
                ->addColumn('checkbox', function ($row) {
                    return '<input class="form-check-input" type="checkbox"  id="select-all"  data-kt-check-target="#kt_ecommerce_category_table .form-check-input" value="1" data-id="'.$row->id.'">';
                })
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('type', function ($row) {
                    return $row->type->name;
                })
                ->addColumn('categories', function ($row) {
                    return $row->categories->pluck('name')->implode(', ');
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        return '<div class="badge badge-light-success">'. 'فعال'.'</div>';
                    } else {
                        return '<div class="badge badge-light-danger">'. 'غير فعال' .'</div>';
                    }
                })
                ->rawColumns(['actions', 'checkbox','status'])
                ->make(true);
        }
        return view('cms.game.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cms.game.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:games|max:255',
            'category_id'=>'required|exists:categories,id',
            'type_id'=>'required|exists:types,id',
            'slug'=>'required|unique:games,slug',
            'status'=>'required|in:active,inactive',
        ]);

        $data = $request->only(['name','slug','type_id','description']);

        $data['status'] = $request->input('status') == 'active' ? 'active' : 'inactive';

        $uploadedFiles = session()->get('uploaded_files', []);
        if ($request->hasFile('images')) {
            $images = $this->uploadService->moveMedia($uploadedFiles, 'images/games');
            foreach ($images as $image) {
                $data['image']= $image;
            }
        }

        $game = Game::query()->create($data);

        if ($request->has('category_id')) {
            $game->categories()->attach($request->category_id);
        }

        if ($game) {
            return ControllerHelper::generateResponse('success','تم اضافة اللعبة بنجاح',200);
        }else{
            return ControllerHelper::generateResponse('error','فشلت عملية الاضافة حاول مرة اخرى !',500);
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
        return view('cms.game.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->request->add(['id' => $id]);
        $request->validate([
            'name' => 'required|unique:games|max:255,games,'.$id,
            'category_id'=>'required|exists:categories,id',
            'type_id'=>'required|exists:types,id',
            'slug'=>'required|unique:games,slug',
            'status'=>'required|in:active,inactive',

        ]);
        $game = Game::query()->find($id);
        $data = $request->only(['name','slug','type_id']);
        if ($game){
            if ($request->hasFile('image')) {
                if ($game->icon) {
                    $imagePath =public_path('storage/'.$game->icon);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
                $game = $this->uploadService->uploadImage($request, 'image', 'images/games');
                $data['image'] = $game;

            }else{
                $data['image'] = $game->image;
            }
        }
        $is_Updated = $game->update($data);
        if ($is_Updated) {
            return ControllerHelper::generateResponse('success','تم تعديل اللعبة بنجاح',200);
        }else{
            return ControllerHelper::generateResponse('error','فشلت عملية التعديل حاول مرة اخرى !',500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $game = Game::query()->find($id);
        if ($game){
            if ($game->image){
                $imagePath = public_path('storage/'.$game->image);
                if (file_exists($imagePath)){
                    unlink($imagePath);
                }
            }
        }
        $is_Deleted = $game->delete();
        if ($is_Deleted){
            return ControllerHelper::generateResponse('success','تم حذف اللعبة بنجاح',201);
        }else{
            return ControllerHelper::generateResponse('error','فشلت عملية الحذف، حاول مرة اخرى',500);

        }
    }

    public function storeMedia(Request $request, UploadService $UploadService)
    {
        $path = storage_path('tmp/uploads');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('images');

        if (!$file) {
            return response()->json(['error' => 'No file uploaded.'], 400);
        }

        $name = uniqid() . '_' . trim($file->hashName());
        $file->move($path, $name);

        // Store the file path in the session
        $uploadedFiles = session()->get('uploaded_files', []);
        $uploadedFiles[] = $name;
        session()->put('uploaded_files', $uploadedFiles);

        return response()->json([
            'name' => $name,
            'original_name' => $file->hashName(),
        ]);
    }
}
