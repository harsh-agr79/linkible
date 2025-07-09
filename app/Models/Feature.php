<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = [
        'title', 'description', 'linkible', 'linkible_boolean', 'other_agencies', 'other_agencies_boolean'
    ];

    protected $casts = [
        'linkible_boolean' => 'boolean',
        'other_agencies_boolean' => 'boolean' 
    ];
}
