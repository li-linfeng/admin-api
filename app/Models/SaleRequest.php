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
        'upload_ids',
        'remark',
        'user_id',
        'sale_num',
        'leader_id',
        'handle_user_id',
    ];
    protected $statusArr = [
        "open"   => "新建",
        "handle" => "处理中",
        "finish" => "完成",
        "cancel" => "取消",
    ];

    public function uploads()
    {
        return $this->hasMany(Upload::class, 'source_id', 'id')->where('source_type', 'sale_request');
    }

    public function getStatusCnAttribute()
    {
        return $this->statusArr[$this->status];
    }
}
