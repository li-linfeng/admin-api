<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ResourceImport;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        return $this->response()->array([
            "code" => 20000,
            "data" => "asdasdasd",
        ]);
    }
}
