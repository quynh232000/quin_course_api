<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Illuminate\Http\Request;

class JwtMiddleware extends BaseMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = JWTAuth::getToken();
        if (!$token) {
            return response()->json(['status' => false, 'message' => 'Token not found!'], 200);
        }
        
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            // return $e;
            if ($e instanceof TokenInvalidException) {
                return response()->json(['status' => false, 'message' => 'Token is Invalid!'], 200);
            } else if ($e instanceof TokenExpiredException) {
                return response()->json(['status' => false, 'message' => 'Token is Expired!'], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'Authorization Token not found!'], 200);
            }
        }
        return $next($request);
    }
}
