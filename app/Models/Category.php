<?php

namespace App\Models;

use App\Models\Filters\CategoryFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory,CategoryFilter;


    protected $guarded = [];


    public function children()
    {
        return $this->hasMany(Material::class, "category_id", "id")->whereIn('type', ['assembly','single-component']);
    }

    public function handler()
    {
        return $this->hasOne(User::class, 'id', 'handler_id');
    }
}
