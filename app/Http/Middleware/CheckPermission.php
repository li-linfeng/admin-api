<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth('api')->user();
        $permissions = [];
        foreach($user->roles as $role){
            $permissions = array_merge($permissions,$role->permissions->pluck('permission')->toArray());
        }
        $result  = collect($permissions)->unique()->values()->toArray();
        
        $route = request()->route()->getName();
        //判断是否拥有超级管理员权限
        if (in_array('*', $result) || in_array( $route, $result)){
            $request->is_super =  in_array('*', $result);
            $request->user_id =  $user->id;
            return $next($request);
        }
        abort(403, '无此权限，请联系管理员');
    }

}
