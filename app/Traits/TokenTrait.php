<?php

namespace App\Traits;

trait TokenTrait
{
    /**
     * 根据用户名和密码生成token
     * @param $user
     * @return array
     */
    protected  function  generateAccessTokenForUser($user)
    {
        $token = auth('api')->login($user);
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60  //换成成秒
        ];
    }
}
