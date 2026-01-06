<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Clip extends Model
{
    use HasFactory;

    protected $fillable = [
        'clip_candidate_id',
        'output_path',
        'status',
    ];

    /**
     * Relasi ke Candidate
     */
    public function candidate()
    {
        return $this->belongsTo(ClipCandidate::class);
    }

    /**
     * Helper: cek apakah clip selesai
     */
    public function isDone(): bool
    {
        return $this->status === 'done';
    }
}
