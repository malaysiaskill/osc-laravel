<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role = null)
    {
        if ($role != null)
        {
            if (strtolower($role) == 'administrator')
            {
                if (Auth::check() && (Auth::user()->role == 'super-admin' || Auth::user()->role == 'admin')) {
                    return $next($request);
                } else {
                    return redirect('/access-denied');
                }
            }
            else
            {
                if (Auth::check() && Auth::user()->role == $role) {
                    return $next($request);
                } else {
                    return redirect('/access-denied');
                }
            }
        }
        else
        {
            return redirect('/access-denied');
        }
    }
}
