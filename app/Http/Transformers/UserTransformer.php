<?php

namespace App\Http\Transformers;

use App\Models\User;

class UserTransformer extends BaseTransformer
{

    protected $availableIncludes = ['user_account', 'avatar', 'friends'];

    protected $is_child = false;

    public function transform(User $user)
    {
        if ($this->is_child) {
            return [
                'id'            => $user->id,
                'nickname'      => $user->nickname,
                'avatar_url'    => optional($user->avatar)->url ?: "",
            ];
        }

        return [
            'id'            => $user->id,
            'nickname'      => $user->nickname,
            'avatar_url'    => optional($user->avatar)->url ?: "",
            'friends_count' => optional($user->friends)->count(),   //如果是在子用户则不需要此字段
            'parent_code'   => optional($user->parent)->share_code ?: "",
            'share_code'    => $user->share_code,
            'mobile'        => $user->mobile,
            'share_url'     => 'http://yoosoul.com/#/share',
        ];
    }

    public function includeFriends(User $user)
    {
        $route = request()->route()->getName();
        if ($user->friends->isEmpty()) {
            return $this->null();
        }
        $transformer = new UserTransformer;

        switch ($route) {
            case "api.user.info":
                $friends = $user->friends->sortByDesc('latest_login_time')->take(8);
                $transformer->setChild();
                break;
            default:
                $friends = $user->friends;
        }
        return $this->collection($friends, $transformer, 'flatten');
    }

    public function includeUserAccount(User $user)
    {
        if (!$user->account) {
            return $this->nullObject();
        }
        return $this->item($user->account, new UserAccountTransformer);
    }


    protected function setChild()
    {
        $this->is_child = true;
    }
}
