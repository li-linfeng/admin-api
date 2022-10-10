<?php

namespace App\Http\Controllers;

use App\Exports\PreSaleExport;
use App\Http\Transformers\PreSaleRequestTransformer;
use App\Models\PreSaleRequest;
use App\Models\SaleRequest;
use App\Models\Todo;
use App\Models\Upload;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PreSaleController extends Controller
{
    //
    public function update(PreSaleRequest $request, Request $req)
    {
    
        $this->canHandle($request);
        $params = app('request')->only([
            'product_type',
            'product_price',
            'pre_pay',
            'product_date',
            'remark',
            'expired_at'
        ]);
        $request->update($params);
        Upload::where('source_type', 'pre_sale')->where('source_id', $request->id)->update(['source_id' => 0]);
        $files = explode(",", $req->upload_ids);
        if (count($files)) {
            Upload::whereIn('id', $files)->update(['source_id' => $request->id, 'source_type' => 'pre_sale']);
        }
        return $this->response()->noContent();
    }

    public function list(Request $request, PreSaleRequestTransformer $transformer)
    {
        $filter = $request->only('filter_status');
        $filter['filter_keyword'] = $request->only('filter_col', 'filter_val');
        $filter['filter_display'] = 1;
        $data = PreSaleRequest::filter($filter)
            ->with(['uploads', 'saleRequest.uploads', 'user', 'handler'])
            ->paginate($request->input('per_page', 10));

        return $this->response()->paginator($data, $transformer, [], function ($resource, $fractal) {
            $fractal->parseIncludes(['uploads', 'sale_request.uploads', 'user', 'handler']);
        });
    }

    public function updateStatus(PreSaleRequest $request, Request $req)
    {
        $this->canHandle($request);
        $request->update($req->only(['status']));
        if($req->status == 'return'){
            $request->return_reason = $req->input('return_reason'); 
            $request->save();
        }

        $content = $req->status == 'return' ? "编号为{$request->sale_num}的需求被退回，请修改后重新发布" : "您创建的编号为{$request->sale_num}的需求已完成";
        $type = $req->status == 'return' ? "sale_request" : "pre_sale";
        Todo::create([
            'content'   => $content,
            'type'      => $type,
            'user_id'   => $request->user_id,
            'source_id' => $request->sale_num
        ]); 

        if ($req->status == 'finish'){
            
        }
        SaleRequest::where('sale_num', $request->sale_num)->update(['status' => $req->status]);
        return $this->response()->noContent();
    }

    protected function canHandle(PreSaleRequest $preRequest)
    {
        if (optional($preRequest->handler)->id ==  request()->user_info['user_id'] ||request()->user_info['is_super']){
            return true;
        }
        abort(403, '没有权限进行此操作');
    }

     
    public function download(Request $request)
    {
        $filter = $request->only('filter_status');
        $filter['filter_keyword'] = $request->only('filter_col', 'filter_val');
        $filter['filter_display'] = 1;
        $data = PreSaleRequest::filter($filter)
            ->with(['saleRequest', 'user', 'handler'])
            ->get();
            
        return Excel::download(new PreSaleExport($data), 'pre_sale.xlsx');
    }
}
