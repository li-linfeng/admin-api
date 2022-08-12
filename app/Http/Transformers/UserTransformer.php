<?php

namespace App\Http\Transformers;

use App\Models\User;

class UserTransformer extends BaseTransformer
{

    protected $availableIncludes = [];

    protected $is_child = false;

    public function transform(User $user)
    {
        return [
            'id'    => $user->id,
            'name'  => $user->nickname,
            'email' => $user->email,
        ];
    }
}
