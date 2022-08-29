<?php

namespace App\Http\Controllers;

use App\Http\Transformers\UserTransformer;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function  info(UserTransformer $userTransformer)
    {
        $info = auth('api')->user();
        return $this->response()->item($info, $userTransformer);
    }

    public function  index(Request $request,UserTransformer $userTransformer)
    {
        $paginator = User::paginate()
        return $this->response()->item($info, $userTransformer);
    }
}
