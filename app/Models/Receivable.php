<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receivable extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'debtor_name',
        'total',
        'remaining_amount',
        'due_date',
        'status',
        'note',
    ];

    protected $casts = [
        'due_date' => 'date',
        'total' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}