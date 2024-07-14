<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\EditableResource;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Facades\DB;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show');
    }
    public function index()
    {
        $posts = Post::withCount('likes')->orderBy('likes_count', 'desc')->get();
        return PostResource::collection($posts);
        // $userPosts = Auth::user()->posts;
    }
    public function viewer()
    {
        $post = Post::orderBy('views', 'desc')->get();
        return PostResource::collection($post);
        // $userPosts = Auth::user()->posts;
    }

    public function show(Request $request, Post $post)
    {
        $post->increment('views');
        $this->authorize('view', $post);

        // Check if the authenticated user owns the post
        if (Auth::user()->id !== $post->user_id) {
            return new PostResource($post);
        }

        // L{oad the likes count
        else {
            $post->loadCount('likes');
            // Return the specific post with likes count
            return new EditableResource($post);
        }
    }


    public function store(PostRequest $request)
    {

        $validated = $request->validated();
        $this->authorize('create', Post::class);
        // Create the post

        $post = Post::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'user_id' => $request->user()->id,
        ]);

        // Return the created post as a response
        return response()->json([
            'status' => true,
            'message' => 'Post created successfully',
            'data' => PostResource::make($post)
        ], 200);
    }
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        // auth()->user()->can('update',[$post]) 
        // Check if the authenticated user owns the post


        // If authorized, delete the post
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }


    public function update(PostRequest $request, Post $post)
    {
        $validated = $request->validated();

        // Authorize the update action
        $this->authorize('update', $post);

        // Update the post with validated data
        $post->update([
            'title' => $validated['title'],
            'content' => $validated['content'], // Assuming 'content' is a field in your Post model
        ]);

        return response()->json(['message' => 'Post updated successfully', 'data' => PostResource::make($post)], 200);
    }


    public function showRecent()
    {

        $posts = Post::orderByDesc('created_at')->get();
        return response()->json([
            'data' => PostResource::collection($posts)
        ], 200);
    }
}
