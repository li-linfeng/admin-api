<?php

namespace App\Models;

use App\Models\Filters\OrderFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, OrderFilter;

    protected $fillable = [
        'order_num',
        'user_id',
        'status',
        'customer_name',
        'total_pay',
        'total_pre_pay',
        'upload_ids',
        'remark',
    ];

    protected $statusArr = [
        "open"   => "待处理",
        "finish" => "完成",
    ];


    public function preSales()
    {
        return $this->hasMany(PreSaleRequest::class, 'order_id', 'id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }


    public function uploads()
    {
        return $this->hasMany(Upload::class, 'source_id', 'id')->where('source_type', 'order');
    }


    public function getStatusCnAttribute()
    {
        return $this->statusArr[$this->status];
    }


    public function setTotalPayAttribute($value)
    {
        $this->attributes['total_pay'] = str_replace(",", "", $value);
    }


    public function setTotalPrePayAttribute($value)
    {
        $this->attributes['total_pre_pay'] = str_replace(",", "", $value);
    }
}
