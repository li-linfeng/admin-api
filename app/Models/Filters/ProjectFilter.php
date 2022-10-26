<?php

namespace App\Models\Filters;


trait ProjectFilter
{
    use BaseFilter;

    public function filterProjectNo($no ='')
    {
        if (!$no){
            return;
        }
        return $this->builder->where('project_no', $no);
    }
}
