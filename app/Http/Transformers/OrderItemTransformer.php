<?php

namespace App\Http\Transformers;

use App\Models\OrderItem;

class OrderItemTransformer extends BaseTransformer
{

    protected $availableIncludes = ['sale_request','order','user', 'handler'];

    public function transform(OrderItem $orderItem)
    {
        return [
            'id'              => $orderItem->id,
            'sale_num'        => $orderItem->sale_num,
            'product_type'    => $orderItem->product_type,
            'product_price'   => formatMoney($orderItem->product_price),
            'pre_pay'         => formatMoney($orderItem->pre_pay),
            'product_date'    => $orderItem->product_date,
            'user_id'         => $orderItem->user_id,
            'status'          => $orderItem->status,
            'status_cn'       => $orderItem->status_cn,
            'order_id'        => $orderItem->order_id,
            'amount'          => $orderItem->amount,
            'created_at'      => $orderItem->created_at->toDateTimeString(),
            'is_start'        => $orderItem->is_start,
            'material_number' => $orderItem->material_number,
        ];
        
    }

    public function includeSaleRequest(OrderItem $orderItem)
    {
        if (!$orderItem->saleRequest) {
            return $this->nullObject();
        }
        return $this->item($orderItem->saleRequest, new SaleRequestTransformer(), 'flatten');
    }

    public function includeOrder(OrderItem $orderItem)
    {
        if (!$orderItem->order) {
            return $this->nullObject();
        }
        return $this->item($orderItem->order, new OrderTransformer(), 'flatten');
    }

    public function includeUser(OrderItem $orderItem)
    {
        if (!$orderItem->user) {
            return $this->nullObject();
        }
        return $this->item($orderItem->user, new UserTransformer());
    }

    public function includeHandler(OrderItem $orderItem)
    {
        if (!$orderItem->handler) {
            return $this->nullObject();
        }
        return $this->item($orderItem->handler, new UserTransformer());
    }
}
