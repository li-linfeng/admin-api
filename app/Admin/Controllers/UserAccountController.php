<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Services\UserMuseAccountService;
use Illuminate\Http\Request;

class UserAccountController extends Controller
{
    public function addDaysToUser(Request $request, UserMuseAccountService $userMuseAccountService)
    {
        if (!User::find($request->user_id)) {
            abort('422', '要操作的用户不存在');
        }
        $admin = auth('admin')->user();
        $add_days = $request->input('add_days');
        $userMuseAccountService->purchase($request->user_id, $add_days, $admin->id);
        return $this->response()->noContent();
    }
}
