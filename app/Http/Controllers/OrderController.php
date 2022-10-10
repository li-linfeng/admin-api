<?php

namespace App\Http\Controllers;

use App\Exports\OrderExport;
use App\Http\Transformers\OrderItemTransformer;
use App\Models\Handler;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PreSaleRequest;
use App\Models\Todo;
use App\Models\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
            $handler = Handler::where('product_type', $item->category)->first();
            if (optional($handler)->handler_id){
                //插入代办
                Todo::create([
                    'content'   => "编号{$order->order_num}的订单中需求编号{$item->sale_num}的项目待处理",
                    'type'      => 'order',
                    'user_id'   => optional($handler)->handler_id,
                    'source_id' => $order->order_num
                ]); 
            }
            return [
                'sale_num'      => $item->sale_num,
                'amount'        => $items[$item->id],
                'product_type'  => $item->product_type,
                'category_name' => $item->category,
                'product_price' => $item->product_price,
                'pre_pay'       => $item->pre_pay,
                'product_date'  => $item->product_date,
                'user_id'       => $item->user_id,
                'status'        => 'open',
                'order_id'       => $order->id, 
                'created_at'    => Carbon::now()->toDateTimeString(),
            ]; 

            //
           

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
        $filter['filter_display'] = 1;
        $paginator = Order::filter($filter)
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

    public function download(Request $request)
    {
        $filter = $request->only('filter_status');
        $filter['filter_keyword'] = $request->only('filter_col', 'filter_val');
        $filter['filter_display'] = 1;
        $data = Order::filter($filter)
            ->with(['orderItems.saleRequest','orderItems.user','orderItems.handler'])
            ->withCount('orderItems')
            ->get();

        $result = [];

        foreach ($data as $item) {
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
        return Excel::download(new OrderExport($result), 'order.xlsx');
    }
}
