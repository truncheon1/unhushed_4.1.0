<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role, $permission = null)
    {

        if(!$request->user()) {
            return redirect(get_path().'/login')->with('status','You must be logged in to access this resource');
        }

        $roles = strstr($role, "|") ? explode("|", $role) : [$role];
        $role_found = false;
        foreach($roles as $role){
            if($request->user()->hasRole($role)){
                $role_found = true;
                break;
            }
        }

        if(!$role_found)
            return redirect(get_path().'/login')->with('status','You must be logged in to access this resource');;
            //abort(403);

        if($permission !== null && !$request->user()->can($permission)) {
            return redirect(get_path().'/login')->with('status','You must be logged in to access this resource');;
              //abort(403);
        }

        return $next($request);
    }
}
