<?php

namespace App\Models\Filters;


trait BaseFilter
{

    protected $builder;
    protected $filters;

    // 查询器
    public function scopeFilter($query, array $validated)
    {
        $this->builder   = $query;
        $this->validated = $validated;
        foreach ($validated as $name => $value) {
            $name = camelize($name); //下划线转驼峰
            if (method_exists($this, $name)) {
                call_user_func_array([$this, $name], array_filter([$value], function ($value) {
                    return ($value !== null && $value !== false && $value !== '');
                }));
            }
        }
        return $this->builder;
    }
}
