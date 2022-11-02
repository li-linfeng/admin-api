<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $filename = $request->file('file')->getClientOriginalName();
        if(!preg_match('/^[\x{4e00}-\x{9fa5}A-Za-z0-9\-\_\.\(\（\)\）]*$/u', $filename)){
            abort("422", "文件名仅支持字母,数字,汉字,括号,下划线(_),短划线(-)");
        }


        $path = $request->file('file')->storeAs(
            'uploads/' . date("Ymd"),
             $filename,
            'public'
        );

        $file = Upload::create([
            'type'        => $request->type,
            'source_type' => $request->source_type,
            'path'        => $path,
            'filename'    => $filename,
        ]);
        $data = $file->toArray();
        $data['name'] = $data['filename'];
        return $this->response()->array($data);
    }
}
