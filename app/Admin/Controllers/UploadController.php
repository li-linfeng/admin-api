<?php

namespace App\Admin\Controllers;

use App\Admin\Services\UploadService;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload(UploadService $uploadService)
    {


        $resource = $uploadService->upload();

        return $this->response()->array([
            'id' => $resource->id,
            'url' => $resource->url

        ]);
    }


    public function uploadBySrc(Request $request, UploadService $uploadService)
    {
        $src = $request->input('url');

        $resource = $uploadService->uploadBySrc($src);
        return $this->response()->array([
            'id' => $resource->id,
            'url' => $resource->url
        ]);
    }
}
