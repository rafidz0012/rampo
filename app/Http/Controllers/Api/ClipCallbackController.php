<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Clip;
use App\Models\ClipCandidate;

class ClipCallbackController extends Controller
{
    public function store(Request $request)
    {
        \Log::info('CLIP CALLBACK MASUK', $request->all());

        $request->validate([
            'candidate_id' => 'required|exists:clip_candidates,id',
            'status'       => 'required|string',
            'output_path'  => 'nullable|string',
        ]);

        $candidate = ClipCandidate::findOrFail($request->candidate_id);

        $candidateStatus = $request->status === 'done'
            ? 'clipped'
            : 'failed';

        // update candidate
        $candidate->update([
            'status' => $candidateStatus,
        ]);

        // simpan ke table clips kalau sukses
        if ($request->status === 'done') {
            Clip::create([
                'clip_candidate_id' => $candidate->id,
                'output_path'  => $request->output_path,
                'status'       => 'done',
            ]);
        }

        return response()->json([
            'ok' => true
        ]);
    }
}
