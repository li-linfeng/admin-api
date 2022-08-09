<?php

namespace App\Admin\Transformers;

use App\Models\Video;


class VideoTransformer extends BaseTransformer
{

    public function transform(Video $resource)
    {
        $route = request()->route()->getName();
        switch ($route) {
            default:
                return [
                    'id'          => $resource->id,
                    'url'         => $resource->url,
                    'origin_name' => $resource->origin_name
                ];
        };
    }
}
