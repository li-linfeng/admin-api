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
            '序号',
            '项目编号',
            '客户名称',
            '产品类型',
            '希望货期',
            '创建人',
            '创建时间',
            '处理人',
            '状态',
            '退回原因'
        ]];
        $data = collect($this->data)->map(function ($item) {
           return  [
                'id'            => $item->id,
                'project_no'    => $item->project_no,
                'customer_name' => $item->customer_name,
                'product_type'  => $item->product_type,
                'expect_time'   => $item->expect_time,
                'username'      => $item->user->username,
                'handler_name'  => $item->handler->username,
                'created_at'    => $item->created_at->toDateTimeString(),
                'status_cn'     => $item->status_cn,
                'return_reason' => $item->return_reason,
            ];
        });
        return collect($header)->merge($data);
    }
}
