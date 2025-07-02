<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
     protected $fillable = [
        'title',
        'slug',
        'meta_title',
        'meta_description',
        'content',
        'type',
        'published_at',
        'cover_image',
        'recommendations',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'recommendations' => 'array',
    ];

    // Optional accessor for retrieving recommended blog objects
    public function recommendedPosts()
    {
        return Blog::whereIn('id', $this->recommendations ?? [])->get();
    }
}
