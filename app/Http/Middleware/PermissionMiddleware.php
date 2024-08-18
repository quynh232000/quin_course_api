<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $userRoles = auth('admin')->user()->roles()->toArray();//delete admin 

        if(in_array('Super Admin', $userRoles)){
            return $next($request);
        }
        if (array_intersect($roles, $userRoles)) {
            return $next($request);
        }
        return redirect()->back()->with('error', 'Permission Denied!');

    }
}
