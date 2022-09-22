<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function finish(OrderItem $orderItem)
    {
        if(!$orderItem->material_number){
            abort(422, '请先绑定物料号');
        }
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


    
    public function bindMaterial(OrderItem $orderItem , Request $request)
    {
        $orderItem->update(['material_number' => $request->material_number]);
        return  $this->response()->noContent();
    }


    public function download(OrderItem $orderItem )
    {

      
        //获取关联的文件
        $materials = Material::where('label', $orderItem->material_number)
        ->with([ 'children.files','children.children.files'])
        ->get();

        $items = flattenTree($materials);

    }
}
