<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class OrderExport implements FromCollection,WithEvents
{
    protected $data;

    //構造函數傳值
    public function __construct($data)
    {
        $this->data       = $data;
    }

    protected $merge = [];

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
            '订单编号',
            '创建时间',
            '订单总价',
            '总预付款',
            '需求编号',
            '产品类型',
            '产品名称',
            '产品单价',
            '单套预付款',
            '数量',
            '产品货期',
            '创建人',
            '处理人',
            '备注',
            '物料编号',
            '状态',
        ]];
        $data = collect($this->data)->map(function ($item,$k) {
          if ($item->is_start){
            $index = $k +2;
            $this->merge[$item->order_id]= [
                'start'  => $index,
                'end' => $index +  $item->order->order_items_count-1
            ];
          }
           $tmp=  [
                'order_num'         => $item->order->order_num,
                'created_at'        => $item->order->created_at->toDateTimeString(),
                'total_pay'         => formatMoney($item->order->total_pay),
                'total_pre_pay'     => formatMoney($item->order->total_pre_pay),
                'sale_num'          => $item->sale_num,
                'sale_product_type' => $item->saleRequest->product_type,
                'product_type'      => $item->product_type,
                'product_price'     => formatMoney($item->product_price),
                'pre_pay'           => formatMoney($item->pre_pay),
                'amount'            => $item->amount,
                'product_date'      => $item->product_date,
                'user'              => $item->user->username,
                'handler_name'      => $item->handler->username,
                'remark'            => $item->order->remark,
                'material_number'   => $item->material_number,
                'status_cn'         => $item->status_cn,
            ];
            return $tmp;
        });
        return collect($header)->merge($data);
    }

    public function registerEvents(): array
    {
   
        return [
            AfterSheet::class  => function(AfterSheet $event) {
                $cols = ['A','B','C','D','N'];
                //合并单元格
                foreach($cols as $col){
                    foreach($this->merge as $merge){
                        $event->sheet->getDelegate()->mergeCells($col.$merge['start'].":".$col.$merge['end']);
                    }   
                }
            }
        ];
    }
}

