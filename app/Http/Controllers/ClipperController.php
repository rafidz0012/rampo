<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Video;
use App\Models\ClipCandidate;

class ClipperController extends Controller
{
public function analyze(Request $request)
{
    $request->validate([
        'url' => 'required|url'
    ]);

    $response = Http::timeout(180)->post(
        'http://127.0.0.1:3001/analyze-subtitle',
        ['url' => $request->url]
    );

    if (!$response->successful()) {
        dd('Clipper error', $response->status(), $response->body());
    }

    $data = $response->json();

    if (
        empty($data['video_id']) ||
        empty($data['candidates'])
    ) {
        dd('Invalid response', $data);
    }

    $video = Video::firstOrCreate(
        ['youtube_id' => $data['video_id']],
        ['youtube_url' => $request->url]
    );

    ClipCandidate::where('video_id', $video->id)->delete();

    foreach ($data['candidates'] as $c) {
        ClipCandidate::create([
            'video_id'      => $video->id,
'start_seconds' => $c['start_seconds'],
'end_seconds'   => $c['end_seconds'],
            'duration'      => $c['duration'],
            'score'         => $c['score'],
            'preview'       => $c['preview'],
            'status'        => 'pending'
        ]);
    }

    return back()->with('success', 'Clip candidates berhasil disimpan');
}

}
