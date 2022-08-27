<?php

namespace App\Http\Controllers;

use App\Http\Transformers\PreSaleRequestTransformer;
use App\Models\PreSaleRequest;
use App\Models\Upload;
use Illuminate\Http\Request;

class PreSaleController extends Controller
{
    //
    public function update(PreSaleRequest $request)
    {
        $params = app('request')->only([
            'product_type',
            'product_price',
            'pre_pay',
            'product_date',
            'upload_ids',
            'remark'
        ]);
        $params['status'] = 'finish';
        $request->update($params);
        Upload::where('source_type', 'pre_sale')->where('source_id', $request->id)->update(['source_id' => 0]);
        $files = explode(",", $request->upload_ids);
        if (count($files)) {
            Upload::whereIn('id', $files)->update(['source_id' => $request->id, 'source_type' => 'pre_sale']);
        }
        return $this->response()->noContent();
    }

    public function list(Request $request, PreSaleRequestTransformer $transformer)
    {
        $keyword = [
            $request->filter_col,
            $request->filter_val,
        ];
        $data = PreSaleRequest::filter(['filter_keyword' => $keyword, 'filter_status' => $request->input('filter_status')])
            ->where('user_id', auth('api')->id())
            ->with(['uploads', 'saleRequest.uploads',])
            ->paginate($request->input('per_page', 10));

        return $this->response()->paginator($data, $transformer, [], function ($resource, $fractal) {
            $fractal->parseIncludes(['uploads', 'sale_request.uploads']);
        });
    }
}
