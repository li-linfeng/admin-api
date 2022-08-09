<?php

namespace App\Admin\Transformers;

use App\Models\PlayList;
use Carbon\Carbon;

class PlayListTransformer extends BaseTransformer
{

    protected $availableIncludes = ['channel'];

    public function transform(PlayList $list)
    {
        $route = request()->route()->getName();
        switch ($route) {
            case "admin.media.index":
            case "admin.channel.index":
            case "admin.play_list.list":
                return [
                    'id'   => $list->id,
                    'name' => $list->name,
                ];
            default:
                return [
                    'id'                  => $list->id,
                    'name'                => $list->name,
                    'total_time'          => $list->total_time,
                    'total_play_count'    => $list->total_play_count,
                    'total_collect_count' => $list->total_collect_count,
                    'created_at'          => Carbon::parse($list->created_at)->toDateTimeString(),
                    'video_count'         => $list->video_count,
                    'audio_count'         => $list->audio_count,
                    'is_default'          => $list->is_default,
                    'is_special'          => $list->is_special,
                ];
        }
    }


    public function includeChannel(PlayList $list)
    {
        if (!$list->channel) {
            return $this->nullObject();
        }
        return $this->item($list->channel, new ChannelTransformer);
    }
}
