<?php

namespace App\Models\Filters;


trait SaleRequestFilter
{
    use BaseFilter;


    
    public function filterCustomerType($type ='')
    {
        if (!$type) {
            return;
        }
        return $this->builder->where('customer_type', $type);
    }

    public function filterProductType($type ='')
    {
        if (!$type) {
            return;
        }
        return $this->builder->where('product_type', $type);
    }
}
