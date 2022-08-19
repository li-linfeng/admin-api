<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload(Request $request)
    {

        $path = $request->file('file')->storeAs(
            'uploads/' . date("Ymd"),
            $request->file('file')->getClientOriginalName(),
            'public'
        );

        $file = Upload::create([
            'type' =>  $request->type,
            'path' => $path,
            'filename' => $request->file('file')->getClientOriginalName(),
        ]);
        return $this->response()->array($file->toArray());
    }
}
