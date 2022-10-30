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
        'project_no',
        'handler_type',
    ];

    protected $statusArr = [
        "open"   => "待处理",
        "finish" => "完成",
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }


    public function uploads()
    {
        return $this->hasMany(Upload::class, 'source_id', 'id')->where('source_type', 'order');
    }


     //项目创建人
     public function user()
     {
         return $this->belongsTo(User::class, "user_id", "id");
     }

     
     //项目创建人
     public function project()
     {
         return $this->belongsTo(Project::class, "project_no", "project_no");
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
