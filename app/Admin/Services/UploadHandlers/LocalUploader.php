<?php

namespace App\Admin\Services\UploadHandlers;

use Illuminate\Support\Facades\Storage;

class LocalUploader
{


    public function save()
    {
        $type = request()->type;
        #实现自定义文件上传
        $file = request()->file('resource');
        //获取文件的扩展名
        $exit = $file->getClientOriginalExtension();
        //获取文件的绝对路径
        $path = $file->getRealPath();
        //定义新的文件名
        $filename = request()->type . '/' .  date('Y-m-d') . '/' . uniqid() . '.' . $exit;

        Storage::disk('public')->put($filename, file_get_contents($path));
        $url = Storage::disk('public')->url($filename);
        return  compact('filename', 'url');
    }
}
