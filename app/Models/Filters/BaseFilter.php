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


    // 类型
    public function filterKeyword($data)
    {
        if (!$data || !$data['filter_col'] || !$data['filter_val']) {
            return;
        }

        $col = explode(".", $data['filter_col']);
        if (count($col) >1){
          
            return  $this->builder->whereHas($col[0], function($q) use ( $col ,$data){
                $q->filter([$col[1]=> $data['filter_val']]);
            });
        }
        
        return $this->builder->where($data['filter_col'], "like", "%{$data['filter_val']}%");
    }

    // 类型
    public function filterStatus($status = '')
    {
        if (!$status) {
            return;
        }
        return $this->builder->where('status', $status);
    }

    public function filterDisplay()
    {
        //判断是不是工程人员
        if(in_array('工程人员',request()->user_info['roles']) || request()->user_info['is_super'] ){
            return ;
        }
        if(in_array('销售人员',request()->user_info['roles'])){
            return $this->builder->where('user_id', request()->user_info['user_id']);
        }

        return $this->builder->where('user_id', 0);
    }
}
