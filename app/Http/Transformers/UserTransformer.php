<?php

namespace App\Http\Transformers;

use App\Models\User;

class UserTransformer extends BaseTransformer
{

    protected $availableIncludes = ['roles'];

    public function transform(User $user)
    {
        return [
            'id'       => $user->id,
            'username' => $user->username,
            'email'    => $user->email,
        ];
    }

    public function includeRoles(User $user)
    {
        if ($user->roles->isEmpty()) {
            return $this->null();
        }
        return $this->collection($user->roles, new RoleTransformer(), 'flatten');
    }
}
