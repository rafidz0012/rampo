<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'amount',
        'billing_cycle',
        'next_billing_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'next_billing_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getMonthlyAmount(): float
    {
        return match ($this->billing_cycle) {
            'monthly' => $this->amount,
            'quarterly' => $this->amount / 3,
            'yearly' => $this->amount / 12,
        };
    }
}
