<?php

namespace App\Http\Controllers;

use App\Http\Transformers\UserTransformer;
use App\Models\SystemConfig;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function  info(UserTransformer $userTransformer)
    {
        //     $info = auth('api')->user();
        //     $userTransformer->setDefaultIncludes(['friends', 'user_account']);
        //     $system = SystemConfig::whereIn('name', ['system_group', 'system_pay'])->select('name', 'value', 'expired_at')->get();
        //     return $this->response()->item($info, $userTransformer)->setMeta(['system' => $system->toArray()]);
        return $this->response()->array([
            "code" => 20000,
            "data" => "info",
        ]);
    }

    public function bindShareCode(Request $request, UserService $userService, UserTransformer $userTransformer)
    {
        if (!$request->filled('share_code') || strlen($request->input('share_code')) != 5) {
            abort(422, "分享码填写错误");
        }
        $auth = auth('api')->user();
        if ($auth->parent_id) {
            abort(422, "您已经绑定过邀请人了");
        }
        $user = $userService->binShareCodeToUser($request->share_code);
        return $this->response()->item($user, $userTransformer);
    }
}
