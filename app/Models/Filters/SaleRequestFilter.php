<?php

namespace App\Models\Filters;


trait SaleRequestFilter
{
    use BaseFilter;

    // 类型
    public function filterKeyword($data)
    {
        if (!$data || !$data['filter_col'] || !$data['filter_val']) {
            return;
        }
        return $this->builder->where($data['filter_col'], "like", "%{$data['filter_val']}%");
    }

    public function filterStatus($status = '')
    {
        if (!$status) {
            return;
        }

        return $this->builder->where('status', $status);
    }
}
