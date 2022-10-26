<?php

namespace App\Http\Controllers;

use App\Exports\SaleRequestExport;
use App\Http\Requests\SaleRqRequest;
use App\Http\Transformers\SaleRequestTransformer;
use App\Models\PreSaleRequest;
use App\Models\SaleRequest;
use App\Models\Todo;
use App\Models\Upload;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SaleRequestController extends Controller
{

    public function list(Request $request, SaleRequestTransformer $transformer)
    {
        $filter = $request->only('filter_status');
        $filter['filter_keyword'] = $request->only('filter_col', 'filter_val');
        $filter['filter_display'] = 1;
        $data = SaleRequest::filter($filter)
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
        $types = implode(",", $request->product_type);
        $data['handle_type'] = $types ?$request->product_type[0]: "";
        $data['product_type'] = $types ;

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
        $types = implode(",", $request->product_type);
        $data['handle_type'] = $types ?$request->product_type[0]: "";
        $data['product_type'] = $types ;

        $request->update( $params);

        Upload::where('source_type', 'sale_request')->where('source_id', $saleRqRequest->id)->update(['source_id' => 0]);

        $files = explode(",", $saleRqRequest->upload_ids);
        if (count($files)) {
            Upload::whereIn('id', $files)->update(['source_id' => $saleRqRequest->id, 'source_type' => 'sale_request']);
        }

        $pre =   PreSaleRequest::where('project_no', $request->sale_num)->first();

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
        //插入一条退回代办
        if($request->handler){
            Todo::create([
                'content'   => "编号为{$request->sale_num}的需求待处理",
                'type'      => 'pre_sale',
                'user_id'   => $request->handler->id,
                'source_id' => $request->sale_num
            ]);
        }
        return $this->response()->noContent();
    }

    protected function canHandle(SaleRequest $saleRequest)
    {
        if ($saleRequest->user_id == request()->user_info['user_id'] ||  request()->user_info['is_super']){
            return true;
        }
        abort(403, '没有权限进行此操作');
    }

    
    public function download(Request $request)
    {
        $filter = $request->only('filter_status');
        $filter['filter_keyword'] = $request->only('filter_col', 'filter_val');
        $saleRequests = SaleRequest::filter($filter)
        ->with(['uploads', 'user', 'handler'])
        ->get();
        return Excel::download(new SaleRequestExport($saleRequests), 'sale_request.xlsx');
    }
}
