<?php

namespace App\Admin\Controllers;

use App\Models\SystemConfig;
use Illuminate\Http\Request;

class SystemConfigController extends Controller
{

    public function index(Request $request)
    {
        $data = SystemConfig::get();
        return $this->response()->array($data->toArray());
    }

    public function add(Request $request)
    {
        SystemConfig::create($request->all());
        return $this->response()->noContent();
    }
}
