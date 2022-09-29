<?php

namespace App\Models;

use App\Models\Filters\HandlerFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Handler extends Model
{
    use HasFactory, HandlerFilter;

    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class, 'handler_id', 'id');
    }
}
