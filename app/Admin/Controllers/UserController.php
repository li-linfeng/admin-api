<?php

namespace App\Admin\Controllers;

use App\Admin\Services\AuthService;
use App\Admin\Transformers\UserTransformer;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request, UserTransformer $userTransformer)
    {

        $users = User::filter(['filter_name' => $request->filter_name])
            ->with(['avatar', 'friends', 'account'])
            ->paginate(request()->input('per_page'));
        return $this->response()->paginator($users, $userTransformer);
    }
}
