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
}
