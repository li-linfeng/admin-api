<?php

namespace App\Models;

use App\Models\Filters\ProjectFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory, ProjectFilter;

    protected $guarded = [];
    protected $attributes = [
        "status" => "continue",
    ];


    protected $statusMap = [
        "continue" => "进行中",
        "cancel"   => "已终止",
        "finish"   => "已完成",
    ];

    //项目创建人
    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }



    protected function getStatusCnAttribute()
    {
        return $this->statusMap[$this->status];
    }
}
