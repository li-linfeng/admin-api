<?php

namespace App\Models;

use App\Models\Filters\NewsFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory, NewsFilter;

    protected $statusMap = [
        "edit"   => "编辑中",
        "finish" => "已发布",
    ];


    protected $guarded = [];


    protected function getStatusCnAttribute()
    {
        return $this->statusMap[$this->status];
    }

}
