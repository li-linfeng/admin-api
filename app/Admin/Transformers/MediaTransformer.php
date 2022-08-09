<?php

namespace App\Admin\Transformers;

use App\Models\Resource;
use Carbon\Carbon;

class MediaTransformer extends BaseTransformer
{

    protected $availableIncludes = ['cover_image', 'play_list'];

    public function transform(Resource $resource)
    {
        $route = request()->route()->getName();
        switch ($route) {
            case "admin.media.show":
                return [
                    'id'              => $resource->id,
                    'type'            => $resource->type,
                    'title'           => $resource->title,
                    'description'     => $resource->description,
                    'media_id'        => $resource->media_id,
                    'url'             => $resource->url,
                    'cover_image_id'  => $resource->cover_image_id,
                    'cover_image_url' => optional($resource->coverImage)->url,
                    'created_at'      => Carbon::parse($resource->created_at)->toDateTimeString(),
                    'play_list_id'    => $resource->play_list_id,
                ];
            default:
                return [
                    'id'              => $resource->id,
                    'type'            => $resource->type,
                    'title'           => $resource->title,
                    'description'     => $resource->description,
                    'url'             => $resource->url,
                    'total_time'      => $resource->total_time,
                    'play_count'      => $resource->play_count,
                    'collect_count'   => $resource->collect_count,
                    'created_at'      => Carbon::parse($resource->created_at)->toDateTimeString(),
                ];
        };
    }

    public function includeCoverImage(Resource $resource)
    {
        if (!$resource->coverImage) {
            return $this->nullObject();
        }
        return $this->item($resource->coverImage, new ImageTransformer);
    }

    public function includePlayList(Resource $resource)
    {
        if (!$resource->playList) {
            return $this->nullObject();
        }
        return $this->item($resource->playList, new PlayListTransformer);
    }
}
