<?php

namespace App\Http\Controllers;

use App\Exports\PreSaleExport;
use App\Http\Transformers\PreSaleRequestTransformer;
use App\Models\PreSaleRequest;
use App\Models\SaleRequest;
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
            'category',
            'product_name',
            'product_price',
            'product_date',
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

        $paginator = SaleRequest::filter($filter)
            ->with(['user', 'handler', 'preSales.uploads'])
            ->withCount(['preSales'])
            ->paginate($request->input('per_page', 10));

        $result =[];
        foreach($paginator->items() as $item){
            if ($item->preSales){
                foreach($item->preSales as $sale){
                    $result[]= [
                        'id'            => $item->id,
                        'project_no'    => $item->project_no,
                        'customer_name' => $item->customer_name,
                        'user_name'     => $item->user->username,
                        'handler_name'  => $item->handler->username,
                        'product_name'  => $sale->product_name,
                        'category'      => $sale->category,
                        'product_price' => $sale->product_price,
                        'product_date'  => $sale->product_date,
                        'uploads'       => $sale->uploads->toArray(),
                    ];
                }
            }else{
                $result[]= [
                    'id'            => $item->id,
                    'project_no'    => $item->project_no,
                    'customer_name' => $item->customer_name,
                    'user_name'     => $item->user->username,
                    'handler_name'  => $item->handler->username,
                    'product_name'  => '',
                    'category'      => '',
                    'product_price' => '',
                    'product_date'  => '',
                    'uploads'  => [],
                ];
            }
        }

        $meta = [
            'pagination' => [
                'total' => $paginator->total(),
                'current_page' => $paginator->currentPage(),
                'per_page' => $request->input('per_page', 10),
                'total_pages' => $paginator->lastPage()
            ]
            ];
        return $this->response->array([
            'data' => $result,
            'meta' => $meta
        ]);
    }


    public function bindToSaleRequest(Request $request)
    {   
        $ids = explode(",", $request->pre_sales);
        if(!$ids){
            abort(422, "请添加产品信息");
        }
        PreSaleRequest::whereIn('id',$ids)->update('sale_id', $request->sale_id);
        return $this->response()->noContent();
    }


    public function download(Request $request)
    {
        
        $filter = $request->only('filter_status');
        $filter['filter_keyword'] = $request->only('filter_col', 'filter_val');
        $filter['filter_display'] = 1;

        $paginator = SaleRequest::filter($filter)
            ->with(['user', 'handler', 'preSales'])
            ->withCount(['preSales'])
            ->get();

        $result =[];
        foreach($paginator->items() as $item){
            if ($item->preSales){
                foreach($item->preSales as  $k=>$sale){
                    $is_start = $k == 0 ? 1 : 0;
                    $result[]         = [
                        'id'              => $item->id,
                        'project_no'      => $item->project_no,
                        'customer_name'   => $item->customer_name,
                        'user_name'       => $item->user->username,
                        'handler_name'    => $item->handler->username,
                        'product_name'    => $sale->product_name,
                        'category'        => $sale->category,
                        'product_price'   => $sale->product_price,
                        'product_date'    => $sale->product_date,
                        'uploads'         => $sale->uploads->toArray(),
                        'is_start'        => $is_start,
                        'pre_sales_count' => $item->preSales_count,
                    ];
                }
            }else{
                $result[]= [
                    'id'            => $item->id,
                    'project_no'    => $item->project_no,
                    'customer_name' => $item->customer_name,
                    'user_name'     => $item->user->username,
                    'handler_name'  => $item->handler->username,
                    'product_name'  => '',
                    'category'      => '',
                    'product_price' => '',
                    'product_date'  => '',
                    'is_start'      => 1,
                    'uploads'       => [],
                ];
            }
        }
            
        return Excel::download(new PreSaleExport($result), 'pre_sale.xlsx');
    }


    protected function canHandle(PreSaleRequest $request)
    {

        $saleRequest = $request->load('saleRequest.handler');
        if ($saleRequest->handler->id == request()->user_info['user_id'] ||  request()->user_info['is_super'] ){
            return true;
        }
        abort(403, '没有权限进行此操作');
    }
}
