<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EditableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'user_id' => $this->user_id,
            'username' => $this->user->name,
            'Likes' => $this->likes()->count(),
            'unique_views_count' => $this->uniqueViewsCount(), // Include the unique views count
            'editable' => 'yes'
        ];
    }
}