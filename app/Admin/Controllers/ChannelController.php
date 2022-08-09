<?php

namespace App\Admin\Controllers;

use App\Admin\Requests\ChannelRequest;
use App\Admin\Transformers\ChannelTransformer;
use App\Models\Channel;
use App\Models\Image;
use Illuminate\Http\Request;

class ChannelController extends Controller
{

    public function index(Request $request, ChannelTransformer $channelTransformer)
    {
        $resource = Channel::with(['backgroundImage', 'iconChoose', 'iconUnchoose'])
            ->filter(['filter_name' => $request->name])
            ->paginate($request->input('per_page'));
        return $this->response()->paginator($resource, $channelTransformer, [], function ($resource, $fractal) {
            $fractal->parseIncludes(['default_list', 'special_list', 'background_image', 'icon_choose', 'icon_unchoose', 'play_list']);
        });
    }


    public function store(ChannelRequest $request)
    {
        $data = $request->only([
            'name', 'description',
            'background_image_id',
            // 'icon_choose_id',
            // 'icon_unchoose_id'
        ]);
        $channel = Channel::create($data);
        if ($request->icon_choose_id && $request->icon_unchoose_id) {
            Image::whereIn('id', [$request->icon_choose_id, $request->icon_unchoose_id])->update(['source_type' => 'icon', 'source_id' => $channel->id]);
        }

        if ($request->background_image_id) {
            Image::where('id', $request->background_image_id)->update(['source_type' => 'channel_cover', 'source_id' => $channel->id]);
        }
        return $this->response()->noContent();
    }

    public function update(ChannelRequest $request, Channel $channel)
    {
        // $channel->update($request->only(['name', 'description', 'background_image_id', 'icon_choose_id', 'icon_unchoose_id']));
        $channel->update($request->only(['name', 'description', 'background_image_id']));
        if ($request->icon_choose_id && $request->icon_unchoose_id) {
            Image::whereIn('id', [$request->icon_choose_id, $request->icon_unchoose_id])->update(['source_type' => 'icon', 'source_id' => $channel->id]);
        }

        if ($request->background_image_id) {
            Image::where('id', $request->background_image_id)->update(['source_type' => 'channel_cover', 'source_id' => $channel->id]);
        }
        return $this->response()->noContent();
    }


    public function display(Channel $channel)
    {
        $channel->is_show = app('request')->input('is_show');
        $channel->save();
        return $this->response()->noContent();
    }


    public function list(Request $request, ChannelTransformer $channelTransformer)
    {
        $resource = Channel::with(['backgroundImage'])
            ->filter(['filter_name' => $request->name])
            ->get();
        return $this->response()->collection($resource, $channelTransformer, ['key' => 'flatten']);
    }
}
