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
        'is_pinned',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'recommendations' => 'array',
        'is_pinned' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Blog $blog) {
            if ($blog->is_pinned) {
                Blog::where('type', $blog->type)
                    ->where('id', '!=', $blog->id)
                    ->update(['is_pinned' => false]);
            }
        });
    }

    // Optional accessor for retrieving recommended blog objects
    public function getRecommendedPostsAttribute()
    {
        if (!$this->recommendations || !is_array($this->recommendations)) {
            return collect(); // Return empty collection if null or invalid
        }

        return Blog::whereIn('id', $this->recommendations)->get();
    }
}
