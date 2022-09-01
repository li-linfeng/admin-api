<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Transformers\UserTransformer;
use App\Models\User;
use App\Models\UserRoleRel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function  info(UserTransformer $userTransformer)
    {
        $info = auth('api')->user();
        return $this->response()->item($info, $userTransformer);
    }

    public function  index(Request $request, UserTransformer $userTransformer)
    {
        $paginator = User::with(['roles'])->paginate($request->input('per_page', 10));
        return $this->response()->paginator($paginator, $userTransformer, [], function ($resource, $fractal) {
            $fractal->parseIncludes(['roles']);
        });
    }

    public function  store(UserRequest $request)
    {
        $user = User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $data = collect($request->roles)->map(function ($item) use ($user) {
            return [
                'user_id' => $user->id,
                'role_id' => $item,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ];
        })->toArray();
        UserRoleRel::insert($data);
        return $this->response()->noContent();
    }


    public function  update(UserRequest $request, User $user)
    {
        $user->update([
            'username' => $request->username,
            'email'    => $request->email,
        ]);
        if ($request->password) {
            $user->password = bcrypt($request->password);
            $user->save();
        }
        //更新角色
        UserRoleRel::where('user_id', $user->id)->delete();
        $data = collect($request->roles)->map(function ($item) use ($user) {
            return [
                'user_id' => $user->id,
                'role_id' => $item,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ];
        })->toArray();
        UserRoleRel::insert($data);
        return $this->response()->noContent();
    }


    public function  delete(User $user)
    {
        $user->delete();
        return $this->response()->noContent();
    }

    public function getUserPermissions()
    {
        $user = auth('api')->user();
        $user->load('roles.permissions');
        $permissions = $user->roles->map(function ($role) {
            return $role->permissions->pluck('permission');
        })->flatten()
            ->unique()
            ->values()
            ->toArray();
        return $this->response()->array($permissions);
    }
}
