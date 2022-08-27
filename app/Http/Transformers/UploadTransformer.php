<?php

namespace App\Http\Transformers;

use App\Models\SaleRequest;
use App\Models\Upload;

class UploadTransformer extends BaseTransformer
{
    public function transform(Upload $upload)
    {
        return [
            'id'   => $upload->id,
            'url'  => $upload->url,
            'name' => $upload->filename,
        ];
    }
}
