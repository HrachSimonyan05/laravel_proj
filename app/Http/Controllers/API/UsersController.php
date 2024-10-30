<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;


class UsersController extends Controller
{
    public function index()
    {
        $users = User::with('posts.images')
            ->withCount('followers')
            ->get();

        return response()->json($users, 200);
    }


    public function store(Request $request){
        $validatedData = $request->validate([
            'first_name'=>['string','required','max:255'],
            'last_name'=>['string','required','max:255'],
            'username'=>['string','required','max:255'],
            'email'=>['string','email','required','max:255'],
            'password'=>['string','required','min:8','confirmed'],
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        $user = User::create($validatedData);
        $token = JWTAuth::fromUser($user);
        return response()->json(compact('user','token'),201);

    }

    public function destroy(Request $request){
        $validatedData = $request->validate([
            'user_id'=>['numeric','required'],
        ]);

        $user = User::findOrFail($validatedData['user_id']);
        $user->delete();
        return response()->json([
            'message'=>'User Deleted',
        ],200);
    }
}
