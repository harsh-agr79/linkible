<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
     protected $fillable = [
        'title',
        'slug',
        'meta_title',
        'meta_description',
        'meta_image',
        'content',
    ];
}
