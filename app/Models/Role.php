<?php

namespace App\Models;

use App\Models\Filters\ProjectFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $guarded = [];
    //项目创建人
    public function permissions()
    {
        return $this->hasMany(RolePermissionRel::class, "role_id", "id");
    }
}
