<?php

namespace App\Http\Transformers;

use App\Models\Material;

class MaterialTransformer extends BaseTransformer
{

    protected $availableIncludes = ['children'];


    public $level = 1;


    public function transform(Material $material)
    {
        $route = request()->route()->getName();
        switch($route){
            case "api.categories.index":
                return  [
                    'id'          => $material->id,
                    'name'        => $material->label,
                    'description' => $material->description,
                    'type'        => $material->type,
                    'has_child'   => $material->has_child,
                    'category_id' => $material->category_id,
                    'key'         => $material->label.uniqid(),
                    'status'      => $material->status,
                    'amount'      => $material->pivot ?  $material->pivot->amount: "",
                    'label'       => $material->label,
                    'level'       => $this->level,
                ];
            default :
                return  [
                    'id'          => $material->id,
                    'seq'         => $material->seq,
                    'label'       => $material->label,
                    'description' => $material->description,
                    'type'        => $material->type,
                    'has_child'   => $material->has_child,
                    'category_id' => $material->category_id,
                ];
        }
    }

    public function includeChildren(Material $material)
    {
        if ($material->children->isEmpty()) {
            return $this->null();
        }
        $transformer = new MaterialTransformer();
        $transformer->level = $this->level +1;
        return $this->collection($material->children, $transformer , 'flatten');
    }
}
