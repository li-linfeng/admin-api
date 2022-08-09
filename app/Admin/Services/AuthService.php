<?php

namespace App\Admin\Services;

use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login()
    {
        $user = $this->validateUser();
        $token = auth('admin')->login($user);
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admin')->factory()->getTTL() * 60  //换成成秒
        ];
    }

    protected function validateUser()
    {
        $nickname = request()->input('nickname');
        $password = request()->input('password');
        $user = AdminUser::where('nickname', $nickname)->first();
        if (!$user) {
            abort(422, "用户名错误");
        }

        if (!Hash::check($password, $user->password)) {
            abort(422, "密码错误");
        }
        return $user;
    }
}
