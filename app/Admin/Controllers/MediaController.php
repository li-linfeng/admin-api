<?php

namespace App\Admin\Controllers;

use App\Admin\Requests\MediaRequest;
use App\Admin\Services\MediaService;
use App\Admin\Transformers\MediaTransformer;
use App\Admin\Transformers\VideoTransformer;
use App\Models\Resource;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class MediaController extends Controller
{

    public function index(Request $request, MediaTransformer $mediaTransformer)
    {
        $resource = Resource::filter(['filter_type' => $request->type, 'filter_name' => $request->name])
            ->where('play_list_id', '!=', 0)
            ->with(['playList.channel', 'coverImage', 'video'])
            ->paginate($request->input('per_page'));
        return $this->response()->paginator($resource, $mediaTransformer, [], function ($resource, $fractal) {
            $fractal->parseIncludes(['play_list.channel', 'cover_image']);
        });
    }


    public function store(MediaRequest $mediaRequest, MediaService $mediaService, MediaTransformer $mediaTransformer)
    {
        $media =  $mediaService->store();
        return $this->response()->noContent();
    }

    public function list(Request $request, VideoTransformer $videoTransformer)
    {
        //同步文件
        Artisan::call('resource:findNewResource');
        $resource = Video::filter(['filter_type' => $request->type])
            ->whereDoesntHave('resource')
            ->get();
        return $this->response()->collection($resource, $videoTransformer,  ['key' => 'flatten']);
    }


    public function update(Resource $resource, MediaRequest $mediaRequest, MediaService $mediaService)
    {
        $media =  $mediaService->update($resource);
        return $this->response()->noContent();
    }

    public function show(Resource $resource, MediaTransformer $mediaTransformer)
    {
        return $this->response()->item($resource, $mediaTransformer);
    }
}
