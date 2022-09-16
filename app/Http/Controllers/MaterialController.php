<?php

namespace App\Http\Controllers;

use App\Http\Transformers\MaterialTransformer;
use App\Models\Category;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialController extends Controller
{
    //
    public function index(Request $request, MaterialTransformer $materialTransformer )
    {
       $paginator=  Material::where('type','category')->with(['children'])->filter(['is_show'=>1])->paginate($request->input('per_page',10));
        return $this->response()->paginator($paginator, $materialTransformer, [], function($resource,$fractal){
            $fractal->parseIncludes(['children']);
        });
    }

 
    public function tree(Request $request, )
    {
        Material::filter()->paginate($request->input('per_page',10));
        return $this->response()->noContent();
    }



    public function store(Request $request)
    {
        Material::create($request->only(['label','file_id', 'description','parent_id']));
        return $this->response()->noContent();
    }

    public function getMaterialSeq(Request $request, MaterialTransformer $materialTransformer)
    {
        $category = Category::findOrFail($request->category_id);

        DB::beginTransaction();

        $seq = Material::where('category_id', $request->category_id)->lockForUpdate()->max('seq');
        
    }
}
