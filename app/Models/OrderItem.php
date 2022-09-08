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

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }


}
