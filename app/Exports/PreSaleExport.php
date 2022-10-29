<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PreSaleExport implements FromCollection,WithEvents
{
    protected $data;
    protected $merge = [];
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
            '创建人',
            '处理人',
            '产品类型',
            '产品型号',
            '产品单价',
            '产品货期',
            '状态',
        ]];
        $data = collect($this->data)->map(function ($item,$k) {
            // if ($item->is_start){
            //     $index = $k +2;
            //     $this->merge[$item->order_id]= [
            //         'start'  => $index,
            //         'end' => $index +  $item->order->order_items_count-1
            //     ];
            // }
          return [
                'id'            => ['id'],
                'project_no'    => ['project_no'],
                'customer_name' => ['customer_name'],
                'user_name'     => ['username'],
                'handler_name'  => ['username'],
                'product_name'  => ['product_name'],
                'category'      => ['category'],
                'product_price' => ['product_price'],
                'product_date'  => ['product_date'],
            ];
        });
        return collect($header)->merge($data);
    }

    public function registerEvents(): array
    {
   
        return [
            // AfterSheet::class  => function(AfterSheet $event) {
            //     $cols = ['A','B','C','D','E','J','K'];
            //     //合并单元格
            //     foreach($cols as $col){
            //         foreach($this->merge as $merge){
            //             $event->sheet->getDelegate()->mergeCells($col.$merge['start'].":".$col.$merge['end']);
            //         }   
            //     }
            // }
        ];
    }
}
