<?php

namespace App\Http\Controllers;

use App\Http\Transformers\CategoryTransformer;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
     public function index(Request $request, CategoryTransformer $categoryTransformer)
    {
        $categories = [];
        $filter['filter_keyword'] = $request->only('filter_col', 'filter_val');
        $categories = Category::filter($filter)->with(['children.children.children', 'handler'])->get();
        return $this->response->collection($categories, $categoryTransformer, [], function($resource, $fractal){
            $fractal->parseIncludes(['handler','children.children.children']);
        });
    }



    public function setHandler(Category $category,Request $request)
    {
        $category->update(['handler_id' => $request->handler_id]);
        return $this>response()->noContent();
    }
}
