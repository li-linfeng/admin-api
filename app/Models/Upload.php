<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Upload extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return Storage::disk("public")->url($this->path);
    }
}
