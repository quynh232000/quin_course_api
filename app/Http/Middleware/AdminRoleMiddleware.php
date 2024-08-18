<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (auth('admin')->check()) {
            $userRoles = auth('admin')->user()->roles()->toArray();
            if (array_intersect($roles, $userRoles)) {
                return $next($request);
            }
            auth('admin')->logout();
            return redirect('/auth/login')->with('message', 'You are not allowed to access this page!');
        }
        auth('admin')->logout();

        return redirect('/auth/login')->with('message', 'Please login to access this page!');

    }
}
