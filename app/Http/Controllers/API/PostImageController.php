<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Http\Request;

class PostImageController extends Controller
{
    public function index(){
        $images = PostImage::with('post')->get();
        return response()->json($images,200);

    }

    public function store(Request $request){
        $valiadatedData = $request->validate([
            'post_id' => ['numeric','required'],
            'images' => ['array', 'required','max:5'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,bmp', 'max:2048'],
        ]);

        $post = Post::findOrFail($valiadatedData['post_id']);

        $postImagesCount = $post->images()->count();
        $newImagesCount = count($valiadatedData['images']);

        $availableImagesCount = 5 - $postImagesCount;

        if ($postImagesCount + $newImagesCount > 5){
            return response()->json([
                'message1'=>'Post Can Have Maximum 5 Images',
                'message2'=>"Available Count Of Images To Add: $availableImagesCount",
            ],400);
        }

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
            'message'=>'Images added successfully',
        ],201);

    }

    public function destroy(Request $request){
        $valiadatedData = $request->validate([
            'image_id' => ['numeric','required'],
        ]);

        $image = PostImage::findOrFail($valiadatedData['image_id']);
        $post = $image->post;
        $postImagesCount = $post->images()->count();

        if($postImagesCount <= 1){
            return response()->json([
                'message' => 'Post must have at least 1 images',
            ],400);
        }

        $image->delete();
        return response()->json([
            'message'=>'Image deleted successfully',
        ],200);
    }
}
