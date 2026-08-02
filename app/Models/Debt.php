<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Debt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'creditor_name',
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

    // Relasi balik ke User
    public function user(): BelongsTo
    {
        return $table->belongsTo(User::class);
    }
}