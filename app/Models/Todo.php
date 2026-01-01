<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Todo extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'due_date',
        'reminder_at',
        'priority',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'reminder_at' => 'datetime',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function toggle(): void
    {
        $this->is_completed = !$this->is_completed;
        $this->completed_at = $this->is_completed ? now() : null;
        $this->save();
    }

    public function isOverdue(): bool
    {
        return !$this->is_completed && $this->due_date && $this->due_date->isPast();
    }
}
