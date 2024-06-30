<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use Illuminate\Http\Request;

class likeController extends Controller
{
    public function Likes(Request $request)
    {

        $post = Like::create(
            [
                'user_id' => $request->user()->id,
                'post_id' => $request->input('post_id'),
            ]
        );
    }
}
