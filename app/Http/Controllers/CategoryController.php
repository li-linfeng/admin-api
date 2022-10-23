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
        $categories = Category::filter($filter)->with(['children.children.children'])->get()->map(function($item, $index){          
            $letters = ['A','B','C','D','E','F'];
            $item->seq =  $letters[$index];
            $item->children = makeSeq($item->children);
            return $item;
        });
     
        return $this->response->collection($categories, $categoryTransformer, [], function($resource, $fractal){
            $fractal->parseIncludes(['children.children.children']);
        });
    }
}
