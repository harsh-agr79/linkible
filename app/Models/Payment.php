<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'session_id',
        'payment_intent_id',
        'status',
        'amount',
        'currency',
        'email',
        'order_id',
        'name',
        'contact',
        'address',
        'city',
        'country',
        'company',
        'website',
        'message',
        'pricing_id',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'amount' => 'integer',
    ];

    protected $hidden = [
        'meta',
    ];

    /**
     * Relationship: Payment belongs to a Pricing plan.
     */
    public function pricing()
    {
        return $this->belongsTo(Pricing::class);
    }
}
