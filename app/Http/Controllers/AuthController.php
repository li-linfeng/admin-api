<?php

namespace App\Http\Controllers;

use App\Services\AuthService;

class AuthController extends Controller
{
    public function login(AuthService $authService)
    {
        $token = $authService->login();

        return  $this->response()->array($token);
    }
}
