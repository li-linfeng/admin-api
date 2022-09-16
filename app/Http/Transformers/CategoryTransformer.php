<?php

namespace App\Http\Transformers;

use App\Models\Category;

class CategoryTransformer extends BaseTransformer
{

    protected $availableIncludes = ['children'];


    public function transform(Category $category)
    {
       return  [
            'id'          => $category->id,
            'name'        => $category->name,
            'description' => $category->description,
        ];
    }

    public function includeChildren(Category $category)
    {
        if ($category->children->isEmpty()) {
            return $this->null();
        }
        return $this->collection($category->children, new MaterialTransformer(), 'flatten');
    }
}
