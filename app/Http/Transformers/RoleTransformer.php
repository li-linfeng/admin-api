<?php

namespace App\Http\Transformers;

use App\Models\Role;
use App\Models\User;

class RoleTransformer extends BaseTransformer
{

    protected $availableIncludes = ['permissions'];

    protected $is_child = false;

    public function transform(Role $role)
    {
        return [
            'id'   => $role->id,
            'name' => $role->name,
        ];
    }

    public function includePermissions(Role $role)
    {
        if ($role->permissions->isEmpty()) {
            return $this->null();
        }
        return $this->collection($role->permissions, new PermissionTransformer(), 'flatten');
    }
}
