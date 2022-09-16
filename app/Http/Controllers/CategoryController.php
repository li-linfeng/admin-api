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

        $categories = Category::with('children')->paginate($request->input('per_page',10));
        return $this->response->paginator($categories, $categoryTransformer, [], function($resource, $fractal){
            $fractal->parseIncludes(['children']);
        });
    }

}
