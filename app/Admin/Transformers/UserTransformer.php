<?php

namespace App\Admin\Transformers;

use App\Models\User;
use Carbon\Carbon;

class UserTransformer extends BaseTransformer
{

    protected $availableIncludes = ['user_account', 'avatar', 'friends'];

    protected $is_child = false;

    public function transform(User $user)
    {
        $available_days = Carbon::now()->diffInDays(Carbon::parse($user->account->end_time), false);
        return [
            'id'                 => $user->id,
            'nickname'           => $user->nickname,
            'avatar_url'         => optional($user->avatar)->url,
            'share_code'         => $user->share_code,
            'friends_count'      => $this->is_child ? 0 : optional($user->friends)->count(),
            'total_account_days' => $user->accountLog->sum('add_time'),
            'available_days'     => $available_days > 0 ? $available_days : 0,
            'first_login_time'   => $user->reg_time
        ];
    }
}
