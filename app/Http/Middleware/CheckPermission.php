<?php
    
namespace aidocs\Http\Middleware;

use Closure;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission = null)
    {
        if(!app('Illuminate\Contracts\Auth\Guard')->guest())
        {
            if($request->user()->can($permission))
            {
                return $next($request);
            }

            return $request->ajax() ? '401' : redirect('/login');
        }

        return false;
    }
}
