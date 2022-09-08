<?php

namespace App\Http\Controllers;

use App\Http\Transformers\PreSaleRequestTransformer;
use App\Models\PreSaleRequest;
use App\Models\SaleRequest;
use App\Models\Upload;
use Illuminate\Http\Request;

class PreSaleController extends Controller
{
    //
    public function update(PreSaleRequest $request, Request $req)
    {
        $params = app('request')->only([
            'product_type',
            'product_price',
            'pre_pay',
            'product_date',
            'remark'
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
        $data = PreSaleRequest::filter($filter)
            ->where('user_id', auth('api')->id())
            ->with(['uploads', 'saleRequest.uploads', 'saleRequest.user', 'saleRequest.handler'])
            ->paginate($request->input('per_page', 10));

        return $this->response()->paginator($data, $transformer, [], function ($resource, $fractal) {
            $fractal->parseIncludes(['uploads', 'sale_request.uploads', 'sale_request.user', 'sale_request.handler']);
        });
    }

    public function updateStatus(PreSaleRequest $request, Request $req)
    {
        $request->update($req->only(['status']));
        if($req->status == 'return'){
            $request->return_reason = $req->input('return_reason');
            $request->save();
            SaleRequest::where('sale_num', $request->sale_num)->update(['status' => 'return']);
        }
        return $this->response()->noContent();
    }
}
