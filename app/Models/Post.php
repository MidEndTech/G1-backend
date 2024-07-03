<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;


class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'views'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function likesCount()
    {
        return $this->likes()->count();
    }
    // public function uniqueViewsCount()
    // {
    //     $cacheKey = "post_{$this->id}_views";
    //     $uniqueViews = Cache::get($cacheKey, collect());
    //     return $uniqueViews->count();
    // }
}
