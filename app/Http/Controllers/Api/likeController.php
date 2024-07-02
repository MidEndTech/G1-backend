<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PostResource;
use App\Http\Resources\LikeResource;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\User;
use App\Models\Post;


use Illuminate\Http\Request;

class likeController extends Controller
{

    public function likePost(Request $request, Post $post)
    {
        $user = $request->user();

        // Check if the user has already liked the post
        if (!$post->likes()->where('user_id', $user->id)->exists()) {
            $like = new Like();
            $like->user_id = $user->id;
            $post->likes()->save($like);

            // return response()->json(['message' => 'Post liked'], 200);
            return response()->json([
                'message' => 'Post liked',
                'like' => new LikeResource($post), // Format the like instance using LikeResource
            ], 200);
        }

        return response()->json(['message' => 'Post already liked'], 409);
    }

    public function unLike(Request $request, Post $post)
    {
        $user = $request->user();

        if (!$post->likes()->where('user_id', $user->id)->exists()) {

            return response()->json(['message' => 'Post not liked'], 409);
        }

        $post->likes()->where('user_id', $user->id)->delete();

        return response()->json(['message' => 'liked deleted successfully'], 200);
    }
}
