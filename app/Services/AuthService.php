<?php

namespace  App\Services;

use App\Models\User;
use App\Traits\TokenTrait;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    use TokenTrait;


    public function login()
    {
        $user = $this->validateUser();
        //记录登录日志
        return  $this->generateAccessTokenForUser($user);
    }


    protected function validateUser()
    {
        $name = request()->input('username');
        $password = request()->input('password');
        $user = User::where('username', $name)->first();
        if (!$user) {
            abort(422, "账号不存在");
        }
        if (!Hash::check($password, $user->password)) {
            abort(422, "密码错误");
        }
        return $user;
    }
}
