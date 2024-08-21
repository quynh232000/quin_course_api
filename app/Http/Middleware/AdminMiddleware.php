<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentUrl = url()->current();

        if (auth('web')->user() && auth('web')->user()->role == 'admin') {
            return $next($request);
        }
        Session::put('redirect_url', $currentUrl);

        return redirect('login')->with('errorMess', 'Vui lòng đăng nhập quản trị viên!');
    }
}
