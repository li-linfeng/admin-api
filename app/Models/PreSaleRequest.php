<?php

namespace App\Models;

use App\Models\Filters\PreSaleFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreSaleRequest extends Model
{
    use HasFactory, PreSaleFilter;

    protected $fillable = [
        'sale_num',
        'product_type',
        'product_price',
        'pre_pay',
        'product_date',
        'upload_ids',
        'user_id',
        'status',
        'remark',
        'order_id',
        'need_num'
    ];

    protected $statusArr = [
        "published" => "处理",
        "return"    => "退回",
        "finish"    => "完成",
    ];


    public function uploads()
    {
        return $this->hasMany(Upload::class, 'source_id', 'id')->where('source_type', 'pre_sale');
    }

    public function saleRequest()
    {
        return $this->belongsTo(SaleRequest::class, 'sale_num', 'sale_num');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }


    public function getStatusCnAttribute()
    {
        return $this->statusArr[$this->status];
    }
}
