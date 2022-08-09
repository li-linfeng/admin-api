<?php

namespace App\Admin\Services;

use App\Admin\Services\UploadHandlers\UploadFactory;
use App\Models\Image;
use App\Models\Resource;
use App\Models\Video;
use Exception;


class UploadService
{

    protected $allowed_ext = [
        "video" => ["mp4", "mov"],
        "audio" => ["mp3", "acc"],
        "image" => ["jpg", "png", "jpeg", "gif"]

    ];
    protected $allowed_name = [
        "video" => "视频仅支持mp4,mov格式,当前格式为：",
        "audio" =>  "音频仅支持mp3,acc格式,当前格式为：",
        "image" =>  "图片仅支持jpg,png,jpeg,gif格式,当前格式为："
    ];

    public function upload()
    {
        $type = request()->type;

        $this->validateExt($type);
        try {
            $re = app(UploadFactory::class)->driver()->save();
            return $this->createResource($re['filename'], $re['url']);
        } catch (Exception $e) {
            abort(500, "上传失败,请重试");
            app('log')->info("文件上传失败---" . $e->getMessage());
        }
    }


    public function uploadBySrc($url)
    {
        return $this->createResourceBySrc($url);
    }

    protected function validateExt($type)
    {
        #实现自定义文件上传
        $file = request()->file('resource');

        //获取文件的扩展名
        $exit = $file->getClientOriginalExtension();
        if (!in_array($exit, $this->allowed_ext[$type])) {
            abort(422, $this->allowed_name[$type] . $exit);
        };
        return;
    }


    protected function createResource($filename, $url)
    {

        if (request()->type == "image") {
            return Image::create([
                'path' => $filename,
                'url'  => $url,
                'source_type' => request()->image_type ?: ""
            ]);
        }
        $total_time = getResourceTotalTime($filename);
        return Video::create([
            'type'        => request()->type,
            'path'        => $filename,
            'url'         => $url,
            'total_time'  => $total_time,
            'origin_name' => basename($filename)
        ]);
    }

    protected function createResourceBySrc($url)
    {

        if (request()->type == "image") {
            return Image::create([
                'path' => '',
                'url'  => $url
            ]);
        }
        return Video::create([
            'type'        => request()->type,
            'path'        => '',
            'url'         => $url,
            'total_time'  => request()->input('total_time', 0),
            'origin_name' => request()->input('origin_name', '')
        ]);
    }
}
