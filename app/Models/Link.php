<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{

    protected $fillable = [
        'title',
        'slug',
        'meta_title',
        'meta_description',
        'meta_image',
        'content',
        'parent_id',
    ];

    public function parent()
    {
        return $this->belongsTo(Link::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Link::class, 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

}
