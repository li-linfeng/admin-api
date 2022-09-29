<?php

namespace App\Models;

use App\Models\Filters\OrderItemFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory, OrderItemFilter;

    protected $guarded = [];


    public function saleRequest()
    {
        return $this->hasOne(SaleRequest::class, 'sale_num','sale_num');
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }


    //工厂的处理人
    public function handler()
    {
        return $this->hasOneThrough(User::class, Handler::class, 'product_type', 'id', 'material_number', 'handler_id' )->where('handlers.module', 'api.order');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }



    public function setPrePayAttribute($value)
    {
        $this->attributes['pre_pay'] = str_replace(",", "", $value);
    }

    public function setProductPriceAttribute($value)
    {
        $this->attributes['product_price'] = str_replace(",", "", $value);
    }

}
