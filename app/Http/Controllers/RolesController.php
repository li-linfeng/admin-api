<?php

namespace App\Http\Controllers;

use App\Admin\Controllers\Controller;
use App\Http\Traits\RoleHelper;
use App\Models\Role;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    use RoleHelper;


    public function list()
    {
        $roles = Role::get();
        return $this->response()->array($roles);
    }


    public function store(Request $request)
    {
        $roles = Role::create(['name' => $request->name]);
        return $this->response()->array($roles);
    }

    public function delete(Request $request, Role $role)
    {
        $role->delete();
        return $this->response()->noContent();
    }
}
