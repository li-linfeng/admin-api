<?php

namespace App\Http\Transformers;

use App\Models\Material;

class MaterialTransformer extends BaseTransformer
{

    protected $availableIncludes = ['children'];


    public function transform(Material $material)
    {
       return  [
            'id'          => $material->id,
            'label'       => $material->label,
            'description' => $material->description,
            'created_at' => $material->created_at->toDateTimeString(),
        ];
    }
}
