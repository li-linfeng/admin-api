<?php

namespace App\Models\Filters;


trait MaterialFilter
{
    use BaseFilter;


    public function filterCategoryId($id ='')
    {
        if (!$id){
            return ;
        }
        return $this->builder->where('category_id', $id);
    }

    public function filterShow($show ='')
    {
        if (!$show){
            return ;
        }
        return $this->builder->where('is_show', 1);
    }
}
