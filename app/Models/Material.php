<?php

namespace App\Models;

use App\Models\Filters\MaterialFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory,MaterialFilter;


    protected $guarded = [];

    public function  getSeqAttribute($val) {
        return str_pad($val,4,'0',STR_PAD_LEFT);
    }

    public function children()
    {
        return $this->belongsToMany(Material::class, MaterialRel::class, 'parent_id','child_id')->withPivot('amount');
    }


    public function files()
    {   
        return $this->hasMany(Upload::class, 'source_id', 'id')->where('source_type', 'material');
    }

}
