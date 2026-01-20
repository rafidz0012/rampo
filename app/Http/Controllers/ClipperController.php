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

        if (empty($data['video_id']) || !isset($data['candidates'])) {
            dd('Invalid response structure', $data);
        }

        $video = Video::firstOrCreate(
            ['youtube_id' => $data['video_id']],
            ['youtube_url' => $request->url]
        );

        ClipCandidate::where('video_id', $video->id)->delete();

        if (count($data['candidates']) === 0) {
            ClipCandidate::create([
                'video_id'      => $video->id,
                'start_seconds' => 0,
                'end_seconds'   => 0,
                'duration'      => 0,
                'score'         => 0,
                'preview'       => 'No clip candidate found',
                'status'        => 'pending'
            ]);

            return back()->with('warning', 'Tidak ada clip kandidat, data default dibuat');
        }

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

    public function process(ClipCandidate $candidate)
    {
        // kirim request ke Node (ASYNC)
        $response = Http::timeout(180)->post(
            'http://127.0.0.1:3001/clip',
            [
                'candidate_id' => $candidate->id,
                'video_url'    => $candidate->video->youtube_url,
                'start'        => $candidate->start_seconds,
                'end'          => $candidate->end_seconds,
            ]
        );

        if ($response->failed()) {
            $candidate->update(['status' => 'failed']);
            return back()->with('error', 'Gagal memulai proses clip');
        }

        // update status SETELAH request diterima
        $candidate->update(['status' => 'processing']);

        return back()->with('success', 'Clip sedang diproses');
    }

    public function update(Request $request, ClipCandidate $candidate)
    {
        $validated = $request->validate([
            'start_seconds' => 'required|numeric',
            'end_seconds'   => 'required|numeric',
            'score'         => 'required|numeric',
            'status'        => 'required|string',
        ]);

        $candidate->update($validated);

        return back()->with('success', 'Candidate updated successfully');
    }

}
