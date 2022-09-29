<?php

namespace App\Http\Transformers;

use App\Models\Category;

class CategoryTransformer extends BaseTransformer
{

    protected $availableIncludes = ['children', 'handler'];


    public function transform(Category $category)
    {
       return  [
            'id'          => $category->id,
            'category_id' => $category->id,
            'name'        => $category->name,
            'code'        => $category->code,
            'description' => $category->description,
            'type'        => 'category',
            'key'         => $category->name.uniqid(),
            'amount'      => '',
            'level'       => 0,
        ];
    }

    public function includeChildren(Category $category)
    {
        if ($category->children->isEmpty()) {
            return $this->null();
        }
        return $this->collection($category->children, new MaterialTransformer(), 'flatten');
    }

    public function includeHandler(Category $category)
    {
        if (!$category->handler) {
            return $this->nullObject();
        }
        return $this->item($category->handler, new UserTransformer());
    }
}
