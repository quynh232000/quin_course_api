<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Session;
use Symfony\Component\HttpFoundation\Response;

class IsLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentUrl = url()->current();

        if (auth('admin')->check()) {
            return $next($request);
        }
        Session::put('redirect_url', $currentUrl);

        return redirect()->route('login')->with('errorMess', 'Please login to access this page!');
    }
}
