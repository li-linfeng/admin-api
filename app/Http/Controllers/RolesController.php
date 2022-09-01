<?php

namespace App\Http\Controllers;

use App\Admin\Controllers\Controller;
use App\Http\Traits\RoleHelper;
use App\Http\Transformers\RoleTransformer;
use App\Models\Role;
use App\Models\RolePermissionRel;
use App\Models\UserRoleRel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    use RoleHelper;


    public function index(Request $request, RoleTransformer $roleTransformer)
    {
        $roles = Role::with(['permissions'])->get();
        return $this->response()->collection($roles, $roleTransformer, [], function ($resource, $fractal) {
            $fractal->parseIncludes(['permissions']);
        });
    }


    public function store(Request $request)
    {
        $role =  Role::create(['name' => $request->name]);
        $data = [];
        $permissions = $request->permissions;
        foreach ($permissions as $permission) {
            $data[] = [
                'role_id'    => $role->id,
                'permission' => $permission['value'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ];
        }
        RolePermissionRel::insert($data);
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

    public function update(Role $role, Request $request)
    {
        $role->update(['name' => $request->name]);
        $data = [];
        $permissions = $request->permissions;
        foreach ($permissions as $permission) {
            $data[] = [
                'role_id'    => $role->id,
                'permission' => $permission['value'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ];
        }
        RolePermissionRel::where('role_id', $role->id)->delete();
        RolePermissionRel::insert($data);
        return $this->response()->noContent();
    }
}
