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

        $categories = Category::with(['children.children.children'])->get();
        return $this->response->collection($categories, $categoryTransformer, [], function($resource, $fractal){
            $fractal->parseIncludes(['children.children.children']);
        });
    }

}
