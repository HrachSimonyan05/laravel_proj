<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Follower;
use App\Models\User;
use Illuminate\Http\Request;

class FollowerController extends Controller
{
    public function store(Request $request){
        $validatedData = $request->validate([
            'follower_id' => ['numeric','required'],
            'followed_user_id' => ['numeric','required'],
        ]);

        $follower = User::findOrFail($validatedData['follower_id']);
        $followedUser = User::findOrFail($validatedData['followed_user_id']);



        if(Follower::where('follower_id',$follower->id)->where('followed_user_id',$followedUser->id)->exists()){
            Follower::where('follower_id',$follower->id)->where('followed_user_id',$followedUser->id)->delete();
            return response()->json([
                'message'=>'follower removed',
            ],201);
        } else {
            Follower::create([
                'follower_id' => $follower->id,
                'followed_user_id' => $followedUser->id,
            ]);
            return response()->json([
                'message'=>'follower added',
            ],201);
        }
    }
}
