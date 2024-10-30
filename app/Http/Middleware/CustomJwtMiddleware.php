<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
class CustomJwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');

        if($token && strpos($token,'JWT ') === 0){
            $token = substr($token,4);
            try {
                JWTAuth::setToken($token);
                $user = JWTAuth::authenticate();

                if(!$user) {
                    return response()->json(['error'=>'User not found'],404);
                }
            } catch (JWTException $e){
                return response()->json(['error'=>'Token invalid or expired'],401);
            }
        } else {
            return response()->json(['error'=>'Invalid token format'],400);
        }
        return $next($request);
    }
}
