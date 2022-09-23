<?php

namespace App\Http\Controllers;

use App\Http\Transformers\OrderItemTransformer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PreSaleRequest;
use App\Models\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;


class OrderController extends Controller
{
    public function getOrderNUm()
    {
        return $this->response()->array([
            'uuid' => date("Ymd") . uniqid()
        ]);
    }


    public function store(Request $request)
    {
        if (! $request->items){
            abort(422,"请选择关联的售前工程");
        }

        $order = Order::create(array_merge(
            [
                'order_num' => $request->order_num,
                'status' => 'open',
                'user_id' => auth('api')->id(),
            ],
            $request->only(['order_num', 'customer_name', 'total_pay', 'total_pre_pay', 'remark'])
        ));

        $items = [];
        foreach($request->items as $v){
            $items[$v['id']] = $v['count'];
        }

        $orderItems = PreSaleRequest::whereIn('id', array_keys($items))->get()->map(function ($item) use ($order, $items) {
            return [
                'sale_num'      => $item->sale_num,
                'amount'        => $items[$item->id],
                'product_type'  => $item->product_type,
                'product_price' => $item->product_price,
                'pre_pay'       => $item->pre_pay,
                'product_date'  => $item->product_date,
                'user_id'       => $item->user_id,
                'status'        => 'open',
                'order_id'       => $order->id,
                'created_at'    => Carbon::now()->toDateTimeString(),
            ]; 
        })->toArray();
        OrderItem::insert($orderItems);

        $files = explode(",", $request->upload_ids);
        if (count($files)) {
            Upload::whereIn('id', $files)->update(['source_id' => $order->id, 'source_type' => 'order']);
        }
        return $this->response()->noContent();
    }


    public function list(Request $request, OrderItemTransformer $transformer)
    {
        $filter = $request->only('filter_status');
        $filter['filter_keyword'] = $request->only('filter_col', 'filter_val');
        $paginator = Order::filter($filter)
            ->where('user_id', auth('api')->id())
            ->with(['orderItems.saleRequest', 'uploads','orderItems.user','orderItems.handler'])
            ->withCount('orderItems')
            ->paginate($request->input('per_page', 10));

        $result = [];

        foreach ($paginator->items() as $item) {
            $items = $item->orderItems;
            unset($item->orderItems);
            foreach ($items as $k => $sale) {
                $sale->is_start = 0;
                if ($k == 0) {
                    $sale->is_start = 1;
                }
                $sale->order = $item;
                $result[] = $sale;
            }
        }
        return $this->response()->collection(collect($result), $transformer, [], function ($resource, $fractal) {
            $fractal->parseIncludes(['order.uploads', 'sale_request','user','handler']);
        })->setMeta([
            'pagination' => [
                'total' => $paginator->total(),
                'current_page' => $paginator->currentPage(),
                'per_page' => $request->input('per_page', 10),
                'total_pages' => $paginator->lastPage()
            ]
        ]);
    }
}
