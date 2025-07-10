<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $fillable = [
        'meta_title',
        'meta_description',
        'meta_image',
        'hero_title',
        'hero_description',
        'happy_customers',
        'team_members_count',
        'uptime',
        'countries',
        'our_story',
        'values',
        'team',
    ];

    protected $casts = [
        'values' => 'array',
        'team' => 'array',
    ];
}
