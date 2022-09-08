<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;

class OrderItemController extends Controller
{
    public function finish(OrderItem $orderItem)
    {

        $orderItem->status = 'finish';
        $orderItem->save();
        //判断订单所有item是否都已经完成

        $total = OrderItem::where('order_id', $orderItem->order_id)->count();
        $finished = OrderItem::where('order_id', $orderItem->order_id)->where('status', 'finish')->count();
        
        if ($total == $finished){
            Order::find($orderItem->order_id)->update(['status' => 'finish']);
        }

        $this->response()->noContent();
    }
}
