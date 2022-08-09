<?php

namespace App\Admin\Services;

use App\Models\Image;
use App\Models\PlayList;
use App\Models\Resource;
use App\Models\Video;
use Illuminate\Support\Facades\DB;

class MediaService
{

    public function store()
    {

        $resource = Resource::create([
            'title' => request()->title,
            'description' => request()->description,
            'cover_image_id' => request()->cover_image_id,
            'play_list_id' => request()->play_list_id,
            'media_id'  => request()->media_id
        ]);

        //绑定图片
        $image = Image::where('id', request()->cover_image_id)->update(['source_id' => $resource->id, 'source_type' => 'media_cover']);

        //关联的播放列表数据

        $col = [
            'video' => 'video_count',
            'audio' => 'audio_count',
        ];
        PlayList::where('id', request()->play_list_id)->update([
            $col[$resource->type] => DB::raw("{$col[$resource->type]} +1"),
            'total_time' => DB::raw("total_time + {$resource->total_time}"),
        ]);
        return $resource;
    }


    public function update(Resource $resource)
    {
        //绑定图片
        if (request()->filled('cover_image_id') &&  $resource->cover_image_id != request()->cover_image_id) {
            if ($resource->coverImage) {
                $resource->coverImage->update(['source_id' => 0]);
            }
            $image = Image::where('id', request()->cover_image_id)->update(['source_id' => $resource->id, 'source_type' => 'media_cover']);
        }

        //关联的播放列表数据
        $col = [
            'video' => 'video_count',
            'audio' => 'audio_count',
        ];
        if (request()->filled('media_id') &&  $resource->media_id != request()->media_id) {
            $video = Video::where('id', request()->media_id)->first();
            $old_video = $resource->video;
            if ($video->type != $old_video->type) {
                //修改时间
                $resource->playList->update([
                    $col[$old_video->type] => DB::raw("{$col[$old_video->type]} -1"),
                    'total_time' => DB::raw("total_time - {$video->total_time}"),
                ]);
                $resource->playList->update([
                    $col[$video->type] => DB::raw("{$col[$video->type]} +1"),
                    'total_time' => DB::raw("total_time + {$video->total_time}"),
                ]);
            }
        }

        $resource->update(request()->all());
        return $resource;
    }
}
