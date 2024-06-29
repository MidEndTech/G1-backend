<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Post;

class postController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show');
    }
    public function index()
    {

        $userPosts = Auth::user()->posts;

        return response()->json(['posts' => $userPosts], 200);
    }


    public function show(Request $request, Post $post)
    {
        // Check if the authenticated user owns the post
        if (Auth::user()->id !== $post->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Return the specific post
        return response()->json(['post' => $post], 200);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 400);
        }

        // Create the post
        $post = Post::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'user_id' => $request->user()->id,
        ]);

        // Return the created post as a response
        return response()->json([
            'status' => true,
            'message' => 'Post created successfully',
            'post' => $post
        ], 200);
    }
    public function destroy(Post $post)
    {
        // Check if the authenticated user owns the post
        if (Auth::user()->id !== $post->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // If authorized, delete the post
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }

    public function update(Request $request, Post $post)
    {
        $title = request()->title;
        $content = request()->content;
        // $user_id = request()->user_id;

        $validatedData = $request->validate([
            'title' => ['required', 'min:3'],
            'content' => ['required', 'min:5'],
        ]);

        if (Auth::user()->id !== $post->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        # update submitted post
        $post->update([
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
        ]);
        return response()->json(['message' => 'Post updated successfully', 'post' => $post], 200);
    }
}
