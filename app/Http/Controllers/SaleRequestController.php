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
        $filter = $request->only('filter_status');
        $filter['filter_keyword'] = $request->only('filter_col', 'filter_val');
        $data = SaleRequest::filter($filter)
            ->where('user_id', auth('api')->id())
            ->with(['uploads', 'user', 'handler'])
            ->paginate($request->input('per_page', 10));

        return $this->response()->paginator($data, $transformer, [], function ($resource, $fractal) {
            $fractal->parseIncludes(['uploads', 'user', 'handler']);
        });
    }


    public function store(SaleRqRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = auth('api')->id() ?: 0;
        $sale =  SaleRequest::create($data);
        $ids = explode(",", $request->upload_ids);
        Upload::whereIn('id',  $ids)->update(['source_id' => $sale->id]);
        return $this->response()->noContent();
    }

    public function update(SaleRequest $request, SaleRqRequest $saleRqRequest)
    {
        $this->canHandle($request);
        $params = $saleRqRequest->all();
        $params['status'] = 'open';
        $request->update( $params);

        Upload::where('source_type', 'sale_request')->where('source_id', $saleRqRequest->id)->update(['source_id' => 0]);

        $files = explode(",", $saleRqRequest->upload_ids);
        if (count($files)) {
            Upload::whereIn('id', $files)->update(['source_id' => $saleRqRequest->id, 'source_type' => 'sale_request']);
        }

        $pre =   PreSaleRequest::where('sale_num', $request->sale_num)->first();

        if($pre){
            $pre->update(['status' => 'change']);
        }
        return $this->response()->noContent();
    }

    public function delete(SaleRequest $request)
    {
        $this->canHandle($request);
        $request->delete();
        return $this->response()->noContent();
    }

    public function publish(SaleRequest $request)
    {
        $this->canHandle($request);
        $user_id = auth('api')->id();
        $request->status = 'published';
        $request->save();
        //生成一条
        PreSaleRequest::where('sale_num', $request->sale_num)->delete();
        PreSaleRequest::create(['sale_num' => $request->sale_num, 'user_id' => $user_id, 'category' => $request->product_type]);
        return $this->response()->noContent();
    }

    protected function canHandle(SaleRequest $saleRequest)
    {
        if ($saleRequest->user_id == request()->user_id || request()->is_super){
            return true;
        }
        abort(403, '没有权限进行此操作');
    }

}
