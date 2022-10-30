<?php

namespace App\Http\Transformers;

use App\Models\OrderItem;

class OrderItemTransformer extends BaseTransformer
{

    protected $availableIncludes = ['order','user', 'handler'];

    public function transform(OrderItem $orderItem)
    {
        return [
            'id'              => $orderItem->id,
            'project_no'      => $orderItem->project_no,
            'product_name'    => $orderItem->product_name,
            'category_name'    => $orderItem->category_name,
            'product_price'   => formatMoney($orderItem->product_price),
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


    public function includeOrder(OrderItem $orderItem)
    {
        if (!$orderItem->order) {
            return $this->nullObject();
        }
        return $this->item($orderItem->order, new OrderTransformer());
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
