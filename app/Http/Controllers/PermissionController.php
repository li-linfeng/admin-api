<?php

namespace App\Http\Controllers;

use App\Admin\Controllers\Controller;
use App\Http\Traits\RoleHelper;

class PermissionController extends Controller
{
    use RoleHelper;


    public function permissions()
    {
        $menus = $this->getMenus();
        return $this->response()->array(compact('menus'));
    }


    public function assignPermission()
    {
        $menus = $this->getMenus('all');
        return $this->response()->array(compact('menus'));
    }
}
