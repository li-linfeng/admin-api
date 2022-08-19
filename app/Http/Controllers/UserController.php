<?php

namespace App\Http\Controllers;

use App\Http\Transformers\UserTransformer;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function  info(UserTransformer $userTransformer)
    {
        $info = auth('api')->user();
        return $this->response()->item($info, $userTransformer);
    }
}
