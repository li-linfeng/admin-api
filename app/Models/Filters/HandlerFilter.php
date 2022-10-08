<?php

namespace App\Models\Filters;


trait HandlerFilter
{
    use BaseFilter;


    public function filterModule($module = '')
    {
        if(!$module){
            return ;
        }
        return $this->builder->where('module', $module);
    }


    public function filterType($type = '')
    {
        if(!$type){
            return ;
        }
        return $this->builder->where('product_type', $type);
    }

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
