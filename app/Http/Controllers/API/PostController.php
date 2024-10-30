<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(){
        $posts = Post::with('images')
                        ->withCount('likes')
                        ->with('comments')
                        ->get();
        return response()->json($posts,200);
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'user_id'=>['numeric','required'],
            'description_ARM'=>['string','required','max:255'],
            'description_RUS'=>['string','required','max:255'],
            'description_ENG'=>['string','required','max:255'],
            'images' => ['array', 'required','max:5'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,bmp', 'max:2048'],
        ]);


        $user = User::findOrFail($validatedData['user_id']);

        $post = Post::create([
            'user_id' => $user->id,
            'description_ARM' => $validatedData['description_ARM'],
            'description_RUS' => $validatedData['description_RUS'],
            'description_ENG' => $validatedData['description_ENG'],
        ]);

        if($request->hasFile('images')){
            foreach ($request->file('images') as $image){
                $path = $image->store('post_images','public');

                PostImage::create([
                    'post_id' => $post->id,
                    'image_path' => $path,
                ]);
            }
        }

        return response()->json([
            'message'=>'Post created',
            'Post'=>$post
        ],201);

    }

    public function destroy(Request $request){
        $validatedData = $request->validate([
            'post_id' => ['numeric','required'],
        ]);

        $post = Post::findOrFail($validatedData['post_id']);
        $post->delete();
        return response()->json([
            'message'=>'Post Deleted',
        ],200);
    }
}
