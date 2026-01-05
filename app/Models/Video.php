<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'youtube_id',
        'youtube_url'
    ];

    public function clips()
    {
        return $this->hasMany(ClipCandidate::class);
    }
}
