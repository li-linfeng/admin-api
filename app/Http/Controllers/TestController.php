<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ResourceImport;

class TestController extends Controller
{
    public function test(Request $request)
    {

        return $this->response()->array(['message' => "ok"]);
    }
}
