<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class ProjectExport implements FromCollection
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
            '项目名称',
            '客户名',
            '项目节点',
            '需求产品',
            '项目预估金额',
            '需求创建时间',
            '创建人',
            '状态',
            '关闭订单原因',
        ]];
        $data = collect($this->data)->map(function ($item) {
           return  [
                'id'               => $item->id,
                'project_no'       => $item->project_no,
                'name'             => $item->name,
                'customer_name'    => $item->customer_name,
                'project_duration' => $item->project_duration,
                'product_name'     => $item->product_name,
                'cost'             => formatMoney($item->cost),
                'created_at'       => $item->created_at->toDateTimeString(),
                'username'         => $item->user->username,
                'status_cn'        => $item->status_cn,
                'close_reason'     => $item->close_reason
            ];
        });
        return collect($header)->merge($data);
    }
}
