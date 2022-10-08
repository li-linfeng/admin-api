<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class PreSaleExport implements FromCollection
{
    protected $data;

    //構造函數傳值
    public function __construct($data)
    {
        $this->data       = $data;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return new Collection($this->createData());
    }

    //業務代碼
    protected function createData()
    {
        $header = [[
            '需求编号',
            '项目编号',
            '客户名称',
            '产品类型',
            '创建人',
            '创建时间',
            '希望货期',
            '处理人',
            '产品型号',
            '产品单价',
            '需预付款',
            '产品货期',
            '价格有效期',
            '状态',
            '退回原因'
        ]];
        $data = collect($this->data)->map(function ($item) {
          return [
                'sale_num'          => $item->saleRequest->sale_num,
                'project_id'        => $item->saleRequest->project_id,
                'customer_type'     => $item->saleRequest->customer_type,
                'sale_product_type' => $item->saleRequest->product_type,
                'username'          => $item->user->username,
                'created_at'        => $item->created_at->toDateTimeString(),
                'expect_time'       => $item->saleRequest->expect_time,
                'handler_name'      => $item->handler->username,
                'product_type'      => $item->product_type,
                'product_price'     => formatMoney($item->product_price),
                'pre_pay'           => formatMoney($item->pre_pay),
                'product_date'      => $item->product_date,
                'expired_at'        => $item->expired_at,
                'status_cn'         => $item->status_cn,
                'return_reason'     => $item->return_reason,
            ];
        });
        return collect($header)->merge($data);
    }
}
