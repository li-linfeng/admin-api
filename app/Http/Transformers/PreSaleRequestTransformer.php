<?php

namespace App\Http\Transformers;

use App\Models\PreSaleRequest;
use Carbon\Carbon;

class PreSaleRequestTransformer extends BaseTransformer
{

    protected $availableIncludes = ['uploads', 'sale_request', 'user', 'handler'];


    public function transform(PreSaleRequest $preSaleRequest)
    {

      return  [
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
            'created_at'    => $preSaleRequest->created_at->toDateTimeString(),
            'return_reason' => $preSaleRequest->return_reason,
            'expired_at'    => $preSaleRequest->expired_at,
        ];

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
        return $this->item($preSaleRequest->saleRequest, new SaleRequestTransformer());
    }

    public function includeUser(PreSaleRequest $preSaleRequest)
    {
        if (!$preSaleRequest->user) {
            return $this->nullObject();
        }
        return $this->item($preSaleRequest->user, new UserTransformer());
    }

    public function includeHandler(PreSaleRequest $preSaleRequest)
    {
        if (!$preSaleRequest->handler) {
            return $this->nullObject();
        }
        return $this->item($preSaleRequest->handler, new UserTransformer());
    }

}
