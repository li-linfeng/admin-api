<?php

namespace App\Admin\Transformers;

use App\Models\Channel;
use Carbon\Carbon;

class ChannelTransformer extends BaseTransformer
{

    protected $availableIncludes = ['default_list', 'special_list', 'background_image', 'icon_choose', 'icon_unchoose', 'play_list'];

    public function transform(Channel $channel)
    {
        $route = request()->route()->getName();
        switch ($route) {
            case "admin.media.index":
            case "admin.play_list.index":
            case "admin.channel.list":
                return [
                    'id'   => $channel->id,
                    'name' => $channel->name,
                ];
            default:
                return [
                    'id'          => $channel->id,
                    'name'        => $channel->name,
                    'is_show'     => $channel->is_show,
                    'description' => $channel->description,
                    'created_at'  => Carbon::parse($channel->created_at)->toDateTimeString(),
                ];
        }
    }


    public function includeDefaultList(Channel $channel)
    {
        if (!$channel->defaultList) {
            return $this->nullObject();
        }
        return $this->item($channel->defaultList, new PlayListTransformer);
    }

    public function includeSpecialList(Channel $channel)
    {
        if (!$channel->specialList) {
            return $this->nullObject();
        }
        return $this->item($channel->specialList, new PlayListTransformer);
    }


    public function includePlayList(Channel $channel)
    {
        if ($channel->playList->isEmpty()) {
            return $this->null();
        }
        $list = $channel->playList->filter(function ($item) {
            return !$item->is_default && !$item->is_special;
        })->values();
        return $this->collection($list, new PlayListTransformer, 'flatten');
    }

    public function includeBackgroundImage(Channel $channel)
    {
        if (!$channel->backgroundImage) {
            return $this->nullObject();
        }
        return $this->item($channel->backgroundImage, new ImageTransformer);
    }
    public function includeIconChoose(Channel $channel)
    {
        if (!$channel->iconChoose) {
            return $this->nullObject();
        }
        return $this->item($channel->iconChoose, new ImageTransformer);
    }
    public function includeIconUnchoose(Channel $channel)
    {
        if (!$channel->iconUnchoose) {
            return $this->nullObject();
        }
        return $this->item($channel->iconUnchoose, new ImageTransformer);
    }
}
