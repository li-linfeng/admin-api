<?php

namespace App\Http\Controllers;

use App\Http\Transformers\OrderTransformer;
use App\Http\Transformers\PreSaleRequestTransformer;
use App\Models\Order;
use App\Models\PreSaleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $order = Order::create(array_merge(
            [
                'order_num' => $request->order_num,
                'status' => 'open',
                'user_id' => auth('api')->id(),
            ],
            $request->only(['order_num', 'customer_name', 'total_pay', 'total_pre_pay', 'remark'])
        ));
        $items = $request->items;
        collect($items)->map(function ($item) use ($order) {
            PreSaleRequest::where('id', $item['id'])->update(['order_id' => $order->id, 'need_num' => $item['count']]);
        });
        return $this->response()->noContent();
    }


    public function list(Request $request, PreSaleRequestTransformer $transformer)
    {
        $keyword = [
            $request->filter_col,
            $request->filter_val,
        ];
        $paginator = Order::filter(['filter_keyword' => $keyword, 'filter_status' => $request->input('filter_status')])
            ->where('user_id', auth('api')->id())
            ->with(['preSales.saleRequest', 'uploads'])
            ->withCount('preSales')
            ->paginate($request->input('per_page', 10));

        $result = [];

        foreach ($paginator->items() as $item) {
            $sales = $item->preSales;
            unset($item->preSales);
            foreach ($sales as $k => $sale) {
                $sale->is_start = 0;
                if ($k == 0) {
                    $sale->is_start = 1;
                }
                $sale->order = $item;
                $result[] = $sale;
            }
        }
        return $this->response()->collection(collect($result), $transformer, [], function ($resource, $fractal) {
            $fractal->parseIncludes(['order.uploads', 'sale_request']);
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
