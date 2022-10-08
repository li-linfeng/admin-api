<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class SaleRequestExport implements FromCollection
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
            '产品性质',
            '希望货期',
            '创建人',
            '创建时间',
            '状态',
            '处理人',
        ]];
        $data = collect($this->data)->map(function ($item) {
           return  [
                'id'            => $item->sale_num,
                'name'          => $item->project_id,
                'customer_type' => $item->customer_type,
                'product_type'  => $item->product_type,
                'expect_time'   => $item->expect_time,
                'username'      => $item->user->username,
                'created_at'    => $item->created_at->toDateTimeString(),
                'status_cn'     => $item->status_cn,
                'handler_name'  => $item->handler->username
            ];
        });
        return collect($header)->merge($data);
    }
}
