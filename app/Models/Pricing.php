<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    protected $fillable = [
        'icon',
        'title',
        'price',
        'short_description',
        'special_tag',
        'features',
        'order',
    ];

    protected $casts = [
        'features' => 'array',
    ];
}
