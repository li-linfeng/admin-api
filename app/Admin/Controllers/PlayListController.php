<?php

namespace App\Admin\Controllers;


use App\Admin\Requests\PlayListRequest;
use App\Admin\Transformers\PlayListTransformer;
use App\Models\PlayList;
use Illuminate\Http\Request;

class PlayListController extends Controller
{

    public function index(PlayListTransformer $transformer)
    {
        $playLists = PlayList::with(['channel'])
            ->filter(['filter_name' => request()->name])
            ->paginate(request()->input('per_page'));
        return $this->response()->paginator($playLists, $transformer, [], function ($source, $fractal) {
            $fractal->parseIncludes(['channel']);
        });
    }


    public function store(PlayListRequest $request)
    {
        $channel_id = $request->channel_id;
        $this->hasDefaultList($channel_id);
        $this->hasSpecialList($channel_id);
        PlayList::create($request->only(['name', 'channel_id', 'is_special', 'is_default']));
        return $this->response()->noContent();
    }

    public function update(PlayListRequest $request, PlayList $list)
    {
        $channel_id = $request->channel_id;
        $this->hasDefaultList($channel_id, $list->id);
        $this->hasSpecialList($channel_id, $list->id);
        $list->update($request->only(['name', 'channel_id', 'is_special', 'is_default']));
        return $this->response()->noContent();
    }

    public function delete(PlayList $list)
    {
        $list->medias()->update(['play_list_id' => 0]);
        $list->delete();
        return $this->response()->noContent();
    }

    public function list(Request $request, PlayListTransformer $transformer)
    {
        $playLists = PlayList::filter([
            'filter_name' => request()->name,
            'filter_channel' => request()->channel_id
        ])
            ->get();
        return $this->response()->collection($playLists, $transformer, ['key' => 'flatten']);
    }

    protected function hasDefaultList($channel_id, $ignore = 0)
    {
        if (request()->is_default) {
            $default = PlayList::where('channel_id', $channel_id)
                ->where('is_default', 1)
                ->filter(['filter_ignore' => $ignore])
                ->first();
            if ($default) {
                abort("该频道已存在默认播放列表");
            }
        }
        return;
    }

    protected function hasSpecialList($channel_id, $ignore = 0)
    {
        if (request()->is_special) {
            $default = PlayList::where('channel_id', $channel_id)
                ->where('is_special', 1)
                ->filter(['filter_ignore' => $ignore])
                ->first();
            if ($default) {
                abort("该频道已存在特殊播放列表");
            }
        }
        return;
    }
}
