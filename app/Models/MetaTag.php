<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetaTag extends Model
{
    protected $fillable = [
        'slug', 'meta_title', 'meta_description', 'meta_image'
    ];
}
