<?php

namespace App\Http\Controllers;

use App\Exports\PreSaleExport;
use App\Models\PreSaleRequest;
use App\Models\SaleRequest;
use App\Models\Upload;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PreSaleController extends Controller
{

    public function list(Request $request)
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
            $pre_sales = $item->preSales->map(function($sal){
                return [
                    'pre_sale_id'   => $sal->id,
                    'product_name'  => $sal->product_name,
                    'category'      => $sal->category,
                    'product_price' => $sal->product_price,
                    'product_date'  => $sal->product_date,
                    'fileList'      => $sal->uploads->toArray(),
                ];
            })->toArray();

            $categories= collect(explode(",",$item->product_type))->map(function($cat){
                return [
                    'key' => $cat,
                    'val' => $cat,
                ];
            })->toArray();


            if ($item->preSales->isEmpty()){
                $result[]= [
                    'id'             => $item->id,
                    'pre_sale_id'    => 0,
                    'categories'     => $categories,
                    'project_no'     => $item->project_no,
                    'customer_name'  => $item->customer_name,
                    'user_name'      => $item->user->username,
                    'handler_name'   => $item->handler->username,
                    'product_name'   => '',
                    'category'       => '',
                    'product_price'  => '',
                    'product_date'   => '',
                    'uploads'        => [],
                    'is_start'       => true,
                    'pre_sale_count' => 1,
                    'status'         => $item->status,
                    'status_cn'      => $item->status_cn,
                    'pre_sales'      => $pre_sales,
                ];
            }else{
                foreach($item->preSales as  $k=>$sale){
                    $is_start       = $k == 0 ? true:false;
                    $pre_sale_count = $item->pre_sales_count;
                    $result[]               = [
                        'id'             => $item->id,
                        'pre_sale_id'    => $sale->id,
                        'categories'     => $categories,
                        'project_no'     => $item->project_no,
                        'customer_name'  => $item->customer_name,
                        'user_name'      => $item->user->username,
                        'handler_name'   => $item->handler->username,
                        'product_name'   => $sale->product_name,
                        'category'       => $sale->category,
                        'product_price'  => $sale->product_price,
                        'product_date'   => $sale->product_date,
                        'uploads'        => $sale->uploads->toArray(),
                        'is_start'       => $is_start,
                        'pre_sale_count' => $pre_sale_count,
                        'status'         => $item->status,
                        'status_cn'      => $item->status_cn,
                        'pre_sales'      => $pre_sales,
                    ];
                }
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


    public function bindToSaleRequest(SaleRequest $request, Request $req)
    {   
        $preSales = $req->pre_sales;
        if(!$preSales){
            abort(422, "请添加产品信息");
        }
        $preSaleIds = PreSaleRequest::where('sale_id', $request->id)->pluck('id')->toArray();
        if($preSaleIds){ //将之前的上传的文件资源id设为0
            Upload:: whereIn('source_id', $preSaleIds)->where('source_type', 'pre_sale')->update(['source_id'=>0]);
        }
        PreSaleRequest::where('sale_id', $request->id)->delete();
        foreach($preSales as $pre){    
            $tmp = [
                'sale_id'       => $request->id,
                'product_name'  => $pre['product_name'],
                'category'      => $pre['category'],
                'product_price' => str_replace(",", "", $pre['product_price']),
                'product_date'  => $pre['product_date'],
            ];
            $preSa = PreSaleRequest::create($tmp);
            $ids = collect($pre['fileList'])->pluck('id')->toArray();
            if($ids){
                Upload::whereIn('id',$ids)->update(['source_id'=> $preSa->id]);
            }
        }
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
        foreach($paginator as $item){
            if ($item->preSales->isEmpty()){
                $result[]= [
                    'id'             => $item->id,
                    'project_no'     => $item->project_no,
                    'customer_name'  => $item->customer_name,
                    'user_name'      => $item->user->username,
                    'handler_name'   => $item->handler->username,
                    'product_name'   => '',
                    'category'       => '',
                    'product_price'  => '',
                    'product_date'   => '',
                    'is_start'       => true,
                    'pre_sale_count' => 1,
                    'status'         => $item->status,
                    'status_cn'      => $item->status_cn,
                ];
            }else{
                foreach($item->preSales as  $k=>$sale){
                    $is_start       = $k == 0 ? true:false;
                    $pre_sale_count = $item->pre_sales_count;
                    $result[]               = [
                        'id'             => $item->id,
                        'project_no'     => $item->project_no,
                        'customer_name'  => $item->customer_name,
                        'user_name'      => $item->user->username,
                        'handler_name'   => $item->handler->username,
                        'product_name'   => $sale->product_name,
                        'category'       => $sale->category,
                        'product_price'  => $sale->product_price,
                        'product_date'   => $sale->product_date,
                        'is_start'       => $is_start,
                        'pre_sale_count' => $pre_sale_count,
                        'status'         => $item->status,
                        'status_cn'      => $item->status_cn,
                    ];
                }
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
