<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    protected $fillable = [
        'title', 'short_title', 'description', 'icon', 'order', 'bullets'
    ];

     protected $casts = [
        'bullets' => 'array',
    ];
}
