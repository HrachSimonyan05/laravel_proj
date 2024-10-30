<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store(Request $request){
        $validatedData = $request->validate([
            'user_id' => ['numeric','required'],
            'liked_post_id' => ['numeric','required'],
        ]);

        $user = User::findOrFail($validatedData['user_id']);
        $post = Post::findOrFail($validatedData['liked_post_id']);

        if (Like::where('user_id',$user->id)->where('liked_post_id',$post->id)->exists()){
            Like::where('user_id',$user->id)->where('liked_post_id',$post->id)->delete();
            return response()->json([
                'message' => 'Post is unliked',
            ],200);
        } else {
            Like::create($validatedData);
            return response()->json([
                'message' => 'Post is liked',
            ],200);
        }

    }
}
