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
            $query = Game::query()->with(['type','categories'])->latest();
            return datatables()->of($query)
                ->addColumn('actions', function ($row) {
                    return view('cms.game.partials.actions', compact('row'))->render();
                })
                ->addColumn('checkbox', function ($row) {
                    return '<input class="form-check-input" type="checkbox"  id="select-all"  data-kt-check-target="#kt_game_table .form-check-input" value="1" data-id="'.$row->id.'">';
                })
                ->addColumn('partials', function ($row) {
                    return view('cms.game.partials.partials', compact('row'))->render();
                })
                ->editColumn('categories', function ($row) {
                    return $row->categories->pluck('name')->implode(', ');
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        return '<div class="badge badge-light-success">'. 'فعال'.'</div>';
                    } else {
                        return '<div class="badge badge-light-danger">'. 'غير فعال' .'</div>';
                    }
                })
                ->rawColumns(['actions', 'checkbox','status','partials'])
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
            'name'       => 'required|unique:games|max:255',
            'category_id'=> 'required|exists:categories,id',
            'type_id'    => 'required|exists:types,id',
            'slug'       => 'required|unique:games,slug',
            'status'     => 'required|in:active,inactive',
            'images.*'   => 'nullable',

        ]);

        $data = $request->only(['name','slug','type_id','description']);

        $data['status'] = $request->input('status') == 'active' ? 'active' : 'inactive';

        if ($request->has('icon')) {
            $icon = $this->uploadService->uploadImage($request, 'icon', 'images/games/icon');
            $data['icon'] = $icon;
        }

        $game = Game::query()->create($data);

        if ($request->has('category_id')) {
            $game->categories()->attach($request->category_id);
        }

        $uploadedFiles = session()->get('uploaded_files', []);
        $savedImages = $this->uploadService->moveMedia($uploadedFiles,'media/games');

        foreach ($savedImages as $imagePath) {
            $game->images()->create([
                'game_id' => $game->id,
                'images' => $imagePath,
            ]);
        }

        session()->forget('uploaded_files');

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
        $game = Game::query()->findOrFail($id);
        return view('cms.game.edit', compact('game'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id,UploadService $UploadService)
    {
        $request->request->add(['id' => $id]);
        $request->validate([
            'name' => 'required|max:255|unique:games,name,'.$id,
            'category_id'=>'required|exists:categories,id',
            'type_id'=>'required|exists:types,id',
            'slug'=>'required|unique:games,slug,'.$id,
            'status'=>'required|in:active,inactive',

        ]);
        $game = Game::query()->find($id);
        $data = $request->only(['name','slug','type_id']);
        if ($game){
            if ($request->has('icon')) {
                if ($game->icon) {
                    $iconPath =public_path('storage/'.$game->icon);
                    if (file_exists($iconPath)) {
                        unlink($iconPath);
                    }
                }
                $icon = $this->uploadService->uploadImage($request, 'icon', 'images/games/icon');
                $data['icon'] = $icon;

            }else{
                $data['icon'] = $game->icon;
            }
        }

        $is_Updated = $game->update($data);

        if ($request->has('category_id')) {
            $game->categories()->sync($request->category_id);
        }

        $uploadedFiles = session()->get('uploaded_files', []);
        $savedImages = $UploadService->moveMedia($uploadedFiles,'media/games');

        foreach ($savedImages as $imagePath) {
            $game->images()->create([
                'game_id' => $game->id,
                'images' => $imagePath,
            ]);
        }

        session()->forget('uploaded_files');

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
            if ($game->icon){
                $iconPath = public_path('storage/'.$game->icon);
                if (file_exists($iconPath)){
                    unlink($iconPath);
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

        // Store the file path in the session (or database)
        $uploadedFiles = session()->get('uploaded_files', []);
        $uploadedFiles[] = $name; // Store the filename
        session()->put('uploaded_files', $uploadedFiles);

        return response()->json([
            'name' => $name,
            'original_name' => $file->hashName(),
        ]);
    }
    public function getGames(Request $request)
    {
        $page = $request->get('page', 1);
        $games = Game::latest()->paginate(10, ['*'], 'page', $page);

        return response()->json($games);

    }
}
