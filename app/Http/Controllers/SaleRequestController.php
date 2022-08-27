<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRqRequest;
use App\Http\Transformers\SaleRequestTransformer;
use App\Models\PreSaleRequest;
use App\Models\SaleRequest;
use App\Models\Upload;
use Illuminate\Http\Request;

class SaleRequestController extends Controller
{
    public function getUniqueId()
    {
        return $this->response()->array([
            'uuid' => date("Ymd") . uniqid()
        ]);
    }


    public function list(Request $request, SaleRequestTransformer $transformer)
    {
        $keyword = [
            $request->filter_col,
            $request->filter_val,
        ];
        $data = SaleRequest::filter(['filter_keyword' => $keyword, 'filter_status' => $request->input('filter_status')])
            ->where('user_id', auth('api')->id())
            ->with(['uploads'])
            ->paginate($request->input('per_page', 10));

        return $this->response()->paginator($data, $transformer, [], function ($resource, $fractal) {
            $fractal->parseIncludes(['uploads']);
        });
    }


    public function store(SaleRqRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = auth('api')->id() ?: 0;
        SaleRequest::create($data);
        //

        return $this->response()->noContent();
    }

    public function update(SaleRqRequest $request, SaleRequest $saleRequest)
    {
        $data = $request->all();
        $data['user_id'] = auth('api')->id() ?: 0;
        $saleRequest->update($request->all());
        Upload::where('source_type', 'sale_request')->where('source_id', $request->id)->update(['source_id' => 0]);
        $files = explode(",", $request->upload_ids);
        if (count($files)) {
            Upload::whereIn('id', $files)->update(['source_id' => $request->id, 'source_type' => 'sale_request']);
        }
        return $this->response()->noContent();
    }

    public function delete(SaleRequest $request)
    {
        $request->delete();
        return $this->response()->noContent();
    }

    public function dispatchHandler(SaleRequest $request)
    {
        // $request->handle_user_id = app('request')->handle_user_id;
        $user_id =  app('request')->handle_user_id ?: auth('api')->id();
        $request->handle_user_id = $user_id;
        $request->status = 'handle';
        $request->save();
        //生成一条
        PreSaleRequest::create(['sale_num' => $request->sale_num, 'user_id' => $user_id,]);
        return $this->response()->noContent();
    }
}
