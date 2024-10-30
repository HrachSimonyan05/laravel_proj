<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request){
        $validatedData = $request->validate([
            'user_id' => ['numeric','required'],
            'post_id' => ['numeric','required'],
            'comment' => ['string','required','max:100'],
        ]);

        $user = User::findOrFail($validatedData['user_id']);
        $post = Post::findOrFail($validatedData['post_id']);

        Comment::create($validatedData);
        return response()->json([
            'message'=>'Comment added to post',
        ],200);
    }

    public function destroy(Request $request){
        $validatedData = $request->validate([
            'user_id' => ['numeric','required'],
            'comment_id' => ['numeric','required'],
        ]);

        $user = User::findOrFail($validatedData['user_id']);
        $comment = Comment::findOrFail($validatedData['comment_id']);
        if ($comment->user_id != $user->id){
            return response()->json([
                'error'=>'Comment does not belong to you',
            ],400);

        } else {
            $comment->delete();
            return response()->json([
                'message'=>'Comment deleted',
            ],200);
        }

    }
}
