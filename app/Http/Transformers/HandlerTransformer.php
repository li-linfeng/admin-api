<?php

namespace App\Http\Transformers;

use App\Models\Category;
use App\Models\Handler;

class HandlerTransformer extends BaseTransformer
{

    protected $availableIncludes = ['user'];


    public function transform(Handler $handler)
    {
       return  [
            'id'           => $handler->id,
            'module'       => $handler->module,
            'module_cn'    => $handler->module_cn,
            'handler_id'   => $handler->handler_id,
            'product_type' => $handler->product_type,
        ];
    }

    public function includeUser(Handler $handler)
    {
        if (!$handler->user) {
            return $this->nullObject();
        }
        return $this->item($handler->user, new UserTransformer());
    }
}
