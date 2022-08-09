<?php

namespace App\Admin\Controllers;

use App\Admin\Services\AuthService;;

class AdminUserController extends Controller
{
    public function login(AuthService $authService)
    {
        $result = $authService->login();

        return $this->response()->array($result);
    }


    public function info()
    {
        $user = auth('admin')->user();

        return $this->response()->array(['user' => $user]);
    }
}
