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

    protected $casts = [
        'project_time' => 'array',
    ];


    protected $statusMap = [
        "continue" => "进行中",
        "cancel"   => "关闭丢单",
        "finish"   => "关闭拿单",
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

    protected function getProjectDurationAttribute()
    {
        return $this->project_time ? implode(" 至 ", $this->project_time) : "--";
    }
}
