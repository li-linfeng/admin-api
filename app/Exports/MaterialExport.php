<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class MaterialExport implements FromCollection
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
            '物料编号',
            '描述',
            '数量',
            '创建日期',
            '备注',
        ]];
        $data = collect($this->data)->map(function ($item) {
            return [
                'index'       => $item['index'],
                'name'        => $item['name'],
                'description' => $item['description'],
                'amount'      => $item['amount'],
                'created_at'  => $item['created_at']
            ];
        });
        return collect($header)->merge($data);
    }
}
