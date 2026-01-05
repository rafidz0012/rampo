<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClipCandidate extends Model
{
    protected $fillable = [
        'video_id',
        'start_seconds',
        'end_seconds',
        'duration',
        'score',
        'preview',
        'status'
    ];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
