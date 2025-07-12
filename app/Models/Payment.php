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

    /**
     * Relationship: Payment belongs to a Pricing plan.
     */
    public function pricing()
    {
        return $this->belongsTo(Pricing::class);
    }
}
