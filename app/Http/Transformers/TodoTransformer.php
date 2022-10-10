<?php

namespace App\Http\Transformers;

use App\Models\Todo;

class TodoTransformer extends BaseTransformer
{



    public function transform(Todo $todo)
    {
       return  [
            'id'         => $todo->id,
            'content'    => $todo->content,
            'type'       => $todo->type,
            'source_id'  => $todo->source_id,
            'created_at' => $todo->created_at->toDateString(),
        ];
    }
}
