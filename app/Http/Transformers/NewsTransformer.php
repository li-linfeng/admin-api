<?php

namespace App\Http\Transformers;

use App\Models\News;

class NewsTransformer extends BaseTransformer
{

    protected $availableIncludes = [''];

  
    public function transform(News $news)
    {
     return [
            'id'         => $news->id,
            'content'    => $news->content,
            'md_content' => $news->md_content,
            'title'      => $news->title,
            'created_at' => $news->created_at->toDateString(),
            'status_cn'  => $news->status_cn
        ];
    }
}
