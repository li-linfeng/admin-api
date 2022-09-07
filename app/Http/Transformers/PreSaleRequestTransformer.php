<?php

namespace App\Http\Transformers;

use App\Models\PreSaleRequest;
use Carbon\Carbon;

class PreSaleRequestTransformer extends BaseTransformer
{

    protected $availableIncludes = ['uploads', 'sale_request', 'order'];


    public function transform(PreSaleRequest $preSaleRequest)
    {
        $route = request()->route()->getName();
        $data =  [
            'id'            => $preSaleRequest->id,
            'sale_num'      => $preSaleRequest->sale_num,
            'product_type'  => $preSaleRequest->product_type,
            'product_price' => formatMoney($preSaleRequest->product_price),
            'pre_pay'       => formatMoney($preSaleRequest->pre_pay),
            'product_date'  => $preSaleRequest->product_date,
            'upload_ids'    => $preSaleRequest->upload_ids,
            'user_id'       => $preSaleRequest->user_id,
            'status'        => $preSaleRequest->status,
            'remark'        => $preSaleRequest->remark,
            'status_cn'     => $preSaleRequest->status_cn,
            'order_id'      => $preSaleRequest->order_id,
            'need_num'      => $preSaleRequest->need_num,
            'created_at'    => $preSaleRequest->created_at->toDateTimeString(),
            'return_reason' => $preSaleRequest->return_reason,
        ];
        if ($route == "api.order.list") {
            $data['is_start'] = $preSaleRequest->is_start;
        }
        return $data;
    }


    public function includeUploads(PreSaleRequest $preSaleRequest)
    {
        if ($preSaleRequest->uploads->isEmpty()) {
            return $this->null();
        }
        return $this->collection($preSaleRequest->uploads, new UploadTransformer(), 'flatten');
    }

    public function includeSaleRequest(PreSaleRequest $preSaleRequest)
    {
        if (!$preSaleRequest->saleRequest) {
            return $this->nullObject();
        }
        return $this->item($preSaleRequest->saleRequest, new SaleRequestTransformer(), 'flatten');
    }

    public function includeOrder(PreSaleRequest $preSaleRequest)
    {
        if (!$preSaleRequest->order) {
            return $this->nullObject();
        }
        return $this->item($preSaleRequest->order, new OrderTransformer(), 'flatten');
    }
}
