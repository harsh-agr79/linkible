<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
     protected $fillable = ['title', 'content', 'meta_title',
        'meta_description',];
}
