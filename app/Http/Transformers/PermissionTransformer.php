<?php

namespace App\Http\Transformers;

use App\Models\RolePermissionRel;

class PermissionTransformer extends BaseTransformer
{

    protected $availableIncludes = [];

    public function transform(RolePermissionRel $permission)
    {
        return [
            'id'         => $permission->id,
            'role_id'    => $permission->role_id,
            'permission' => $permission->permission,
        ];
    }
}
