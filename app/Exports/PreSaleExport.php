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
            '客户名称',
            '创建人',
            '处理人',
            '产品类型',
            '产品型号',
            '产品单价',
            '产品货期',
            '状态',
        ]];
        $data = collect($this->data)->map(function ($item,$k) {
            if ($item['is_start']){
                $index = $k +2;
                $this->merge[$item['id']]= [
                    'start'  => $index,
                    'end' => $index +  $item['pre_sale_count']-1
                ];
            }
          return [
                'id'            => $item['id'],
                'project_no'    => $item['project_no'],
                'customer_name' => $item['customer_name'],
                'user_name'     => $item['user_name'],
                'handler_name'  => $item['handler_name'],
                'category'      => $item['category'],
                'product_name'  => $item['product_name'],
                'product_price' => $item['product_price'],
                'product_date'  => $item['product_date'],
                'status_cn'     => $item['status_cn'],
            ];
        });
        return collect($header)->merge($data);
    }

    public function registerEvents(): array
    {
   
        return [
            AfterSheet::class  => function(AfterSheet $event) {
                $cols = ['A','B','C','D','E','J','K'];
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
