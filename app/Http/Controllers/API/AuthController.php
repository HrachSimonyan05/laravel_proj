<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request){
        $credentials = $request->only('email','password');
        if (!$token = JWTAuth::attempt($credentials)){
            return response()->json(['error'=>'Invalid credentials'],401);
        }

        return response()->json(compact('token'));
    }

    public function getUser(){
        try {
            if(!$user = JWTAuth::parseToken()->authenticate()){
                return response()->json(['error'=>'User not found'],404);
            }
        } catch(\Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
            return response()->json(['error'=>'Token expired'],401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
            return response()->json(['error'=>'Token invalid'],401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e){
            return response()->json(['error'=>'Token not provided'],401);
        }

        return response()->json($user,200);
    }

    public function logout(){
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message'=>'User logged out']);
    }
}
