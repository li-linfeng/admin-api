<?php

namespace App\Models\Filters;


trait SaleRequestFilter
{
    use BaseFilter;

    
    public function filterCustomerName($name ='')
    {
        if (!$name) {
            return;
        }
        return $this->builder->where('customer_name', $name);
    }

    public function filterProjectNo($no ='')
    {
        if (!$no) {
            return;
        }
        return $this->builder->where('project_no', $no);
    }

}
