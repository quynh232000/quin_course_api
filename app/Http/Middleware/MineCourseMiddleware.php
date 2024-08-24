<?php

namespace App\Http\Middleware;

use App\Models\Course;
use Closure;
use Illuminate\Http\Request;
use Redirect;
use Symfony\Component\HttpFoundation\Response;

class MineCourseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $type = 'course_id'): Response
    {
        switch ($type) {
            case 'course_id':
                $courseId = $request->route('id');
                if (!$courseId) {
                    return redirect('/notfund')->with('message', 'Missing course id');
                }
                $course = Course::find($courseId);
                if (!$course) {
                    return redirect('/notfund')->with('message', "Course ID {$courseId} not found");
                }
                if ($course->user_id != auth('admin')->user()->id) {
                    return redirect('/notfund')->with('message', 'You are not authorized to access this course');
                }
                break;

            default:
                # code...
                break;
        }


        return $next($request);
    }
}
