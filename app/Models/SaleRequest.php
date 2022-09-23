<?php

namespace App\Models;

use App\Models\Filters\SaleRequestFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleRequest extends Model
{

    use HasFactory, SaleRequestFilter;

    protected $fillable = [
        'project_id',
        'product_type',
        'customer_type',
        'device_name',
        'driver_type',
        'driver_power',
        'rpm',
        'torque',
        'shaft_one_diameter_tolerance',
        'shaft_two_diameter_tolerance',
        'shaft_one_match_distance',
        'shaft_two_match_distance',
        'shaft_space_distance',
        'remark',
        'user_id',
        'sale_num',
        'expect_time',
        'status',
    ];
    protected $statusArr = [
        "open"      => "销售",
        "published" => "处理",
        "return"    => "退回",
        "finish"    => "完成",
    ];

    public function uploads()
    {
        return $this->hasMany(Upload::class, 'source_id', 'id')->where('source_type', 'sale_request');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function handler()
    {
        return $this->hasOneThrough(User::class, Category::class, 'name', 'id', 'product_type', 'handler_id' );
    }


    public function getStatusCnAttribute()
    {
        return $this->statusArr[$this->status];
    }
}
