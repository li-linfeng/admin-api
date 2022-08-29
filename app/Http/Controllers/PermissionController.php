<?php

namespace App\Http\Controllers;

use App\Admin\Controllers\Controller;
use App\Http\Traits\RoleHelper;
use App\Models\RolePermissionRel;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    use RoleHelper;


    public function permissions()
    {
        $menus = $this->getMenus();
        return $this->response()->array(compact('menus'));
    }


    public function assignPermission(Request $request)
    {
        $role = $request->role;
        $data = [];
        foreach ($request->permissions as $permission) {
            $data[] = [
                'role_id' => $role,
                'permission' => $permission,
            ];
        }
        RolePermissionRel::where('role_id', $role)->delete();
        RolePermissionRel::create($data);
        return $this->response()->noContent();
    }
}
