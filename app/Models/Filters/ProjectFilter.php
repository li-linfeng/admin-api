<?php

namespace App\Models\Filters;


trait ProjectFilter
{
    use BaseFilter;

    // 类型
    public function filterName($name = '')
    {
        if (!$name) {
            return;
        }
        return $this->builder->where('name', "like", "%{$name}%");
    }

    // 类型
    public function filterStatus($status = '')
    {
        if (!$status) {
            return;
        }
        return $this->builder->where('status', $status);
    }
}
