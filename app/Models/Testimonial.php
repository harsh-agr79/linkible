<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'comment',
        'name',
        'designation',
        'profile_image',
        'rating',
        'sort_order',
    ];
}
