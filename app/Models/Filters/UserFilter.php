<?php

namespace App\Models\Filters;


trait UserFilter
{
    use BaseFilter;

    // 类型
    public function filterName($name = '')
    {
        if (!$name) {
            return;
        }
        return $this->builder->where(function ($q) use ($name) {
            $q->where('username', 'like',"%{$name}%")->orWhere('email', 'like',"%{$name}%");
        });
    }
}
