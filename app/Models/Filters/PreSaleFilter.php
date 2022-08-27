<?php

namespace App\Models\Filters;


trait PreSaleFilter
{
    use BaseFilter;

    // 类型
    public function filterKeyWord($keyword = [])
    {
        $arr = array_filter($keyword);
        if (count($arr) != 2) {
            return;
        }

        return $this->builder->where($keyword[0], 'like', "%$keyword[1]%");
    }


    public function filterStatus($status = '')
    {
        if (!$status) {
            return;
        }

        return $this->builder->where('status', $status);
    }
}
