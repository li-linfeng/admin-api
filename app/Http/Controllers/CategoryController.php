<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function list()
    {
        $categories = [];

        Category::withCount('children')->inRandomOrder()->get()->map(function ($item) use (&$categories) {
            $tmp = $item->toArray();
            $tmp['children'] = [];
            $categories[$item->id] = $tmp;
        });
        $tree = [];
        if ($categories) {
            $tree =  makeTree($categories, 0);
        }
        return $this->response->array($tree);
    }


    public  function store(Request $request)
    {
        $data = [
            'label' => $request->label,
            'parent_id' => $request->parent_id,
            'has_child' => $request->has_child,
        ];
        $category = Category::create($data);
        return $this->response->array($category->toArray());
    }


    public  function delete(Category $category)
    {
        Category::where('parent_id', $category->id)->delete();
        $category->delete();
        return $this->response->noContent();
    }
}
