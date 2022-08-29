<?php

namespace App\Http\Controllers;

use App\Admin\Controllers\Controller;
use App\Http\Traits\RoleHelper;
use App\Http\Transformers\RoleTransformer;
use App\Models\Role;
use App\Models\RolePermissionRel;
use App\Models\UserRoleRel;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    use RoleHelper;


    public function index(Request $request, RoleTransformer $roleTransformer)
    {
        $roles = Role::with(['permissions'])->paginate($request->input('per_page', 10));
        return $this->response()->paginator($roles, $roleTransformer, [], function ($resource, $fractal) {
            $fractal->parseIncludes(['permissions']);
        });
    }


    public function store(Request $request)
    {
        $roles = Role::create(['name' => $request->name]);
        return $this->response()->noContent();
    }

    public function delete(Request $request, Role $role)
    {

        RolePermissionRel::where('role_id', $role->id)->delete();
        UserRoleRel::where('role_id', $role->id)->delete();
        $role->delete();
        return $this->response()->noContent();
    }



    public function allPermissions()
    {
        $menus = $this->getMenus();
        return $this->response()->array($menus);
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
