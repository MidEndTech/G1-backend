<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LikeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return
            [
                'isLiked' => "Post liked",
                // 'posts' => $this->likesCount();
                'likes_count' => $this->likes()->count(),

            ];
    }
}
