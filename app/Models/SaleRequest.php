<?php

namespace App\Models;

use App\Models\Filters\SaleRequestFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleRequest extends Model
{

    use HasFactory, SaleRequestFilter;

    protected $fillable = [
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
        'upload_id',
        'remark',
        'user_id',
        'sale_num',
    ];
    protected $statusArr = [
        "open"   => "新建",
        "handle" => "处理中",
        "finish" => "完成",
        "cancel" => "取消",
    ];

    public function upload()
    {
        return $this->hasOne(Upload::class, 'id', 'upload_id');
    }

    public function getStatusCnAttribute()
    {
        return $this->statusArr[$this->status];
    }
}
