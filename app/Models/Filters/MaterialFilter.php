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
        return $this->builder->where('category_id', $id)->where('is_show', 1);
    }

    public function filterLabel($label ='')
    {
        if (!$label){
            return ;
        }
        return $this->builder->where('label','like', "%$label%")->where('is_show', 1)->orWhereHas('children', function($q) use($label){
            $q->where('label','like', "%$label%")->where('is_show', 1)->orWhereHas('children', function($query) use($label){
                $query->where('label','like', "%$label%")->where('is_show', 1);
            });
        });
    }

    public function filterDescription($desc ='')
    {
        if (!$desc){
            return ;
        }
        return $this->builder->where('description','like', "%$desc%")->where('is_show', 1);
    }

    public function filterShow($show ='')
    {
        if (!$show){
            return ;
        }
        return $this->builder->where('is_show', 1);
    }
}
