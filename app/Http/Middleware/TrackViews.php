<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Post;

class TrackViews
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($request->isMethod('get') && $request->route('post')) {
            $post = $request->route('post');
            $ipAddress = $request->ip();
            $cacheKey = "post_{$post->id}_views";

            // Store IP address in cache for 24 hours
            $uniqueViews = Cache::remember($cacheKey, 86400, function () {
                return collect();
            });

            if (!$uniqueViews->contains($ipAddress)) {
                $uniqueViews->push($ipAddress);
                Cache::put($cacheKey, $uniqueViews, 86400);
            }
        }

        return $response;
    }
}
