<?php

namespace App\Http\Transformers;

use App\Models\Order;

class OrderTransformer extends BaseTransformer
{

    protected $availableIncludes = ['pre_sales', 'uploads'];


    public function transform(Order $order)
    {
        $route = request()->route()->getName();

        $data =  [
            'id'            => $order->id,
            'user_id'       => $order->user_id,
            'status'        => $order->status,
            'status_cn'     => $order->status_cn,
            'created_at'    => $order->created_at->toDateTimeString(),
            'customer_name' => $order->customer_name,
            'total_pay'     => $order->total_pay,
            'total_pre_pay' => $order->total_pre_pay,
            'upload_ids'    => $order->upload_ids,
            'remark'        => $order->remark,
            'order_num'     => $order->order_num,
        ];
        if ($route == "api.order.list") {
            $data['pre_sales_count'] = $order->pre_sales_count;
        }
        return $data;
    }


    public function includePreSales(Order $order)
    {
        if ($order->preSales->isEmpty()) {
            return $this->null();
        }
        return $this->collection($order->preSales, new PreSaleRequestTransformer(), 'flatten');
    }


    public function includeUploads(Order $order)
    {
        if ($order->uploads->isEmpty()) {
            return $this->null();
        }
        return $this->collection($order->uploads, new UploadTransformer(), 'flatten');
    }
}
