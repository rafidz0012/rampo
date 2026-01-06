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
     public function getStartTimeFormattedAttribute()
    {
        return $this->formatSeconds($this->start_seconds);
    }

    public function getEndTimeFormattedAttribute()
    {
        return $this->formatSeconds($this->end_seconds);
    }

    private function formatSeconds($seconds)
    {
        if ($seconds === null) return '-';

        $seconds = (float) $seconds;

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = floor($seconds % 60);

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
    }
    public function clips()
    {
        return $this->hasMany(Clip::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
