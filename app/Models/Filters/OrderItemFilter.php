<?php

namespace App\Models\Filters;


trait OrderItemFilter
{
    use BaseFilter;

    public function filterUsername($name = '')
    {
        if (!$name){
            return ;
        }

        return $this->builder->whereHas('user', function($query) use ($name){
            $query->where('username', 'like', "%{$name}%");
        });
    }
}
