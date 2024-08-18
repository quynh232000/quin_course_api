<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeacherInfoMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {

        $userRoles = auth('admin')->user()->roles()->toArray();//delete admin 


        if (array_intersect($roles, $userRoles) && auth('admin')->user()->isVerifyTeacher()) {
            return $next($request);
        }
        return redirect('/teacher/updateinfo')->with('error', 'Update your information to become a teacher then can use features of teacher.');


    }
}
