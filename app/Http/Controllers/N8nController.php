<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ClipCandidate;

class N8nController extends Controller
{
    public function index()
    {
        $candidates = ClipCandidate::with('video')->latest()->limit(50)->get();
        $clips = \App\Models\Clip::with('candidate')->latest()->limit(50)->get();
        return view('n8n.index', compact('candidates', 'clips'));
    }

public function send(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'url' => 'required|url',
        'content' => 'nullable|string',
        'webhook_url' => 'nullable|url',
    ]);

    $webhookUrl = $validated['webhook_url']
        ?? config('services.n8n.webhook_url');

    if (!$webhookUrl) {
        return back()->with('error', 'N8n Webhook URL is not configured.');
    }

    try {
        $response = Http::post($webhookUrl, [
            'title' => $validated['title'],
            'url' => $validated['url'],
            'content' => $validated['content'] ?? null,
            'timestamp' => now()->toIso8601String(),
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Data sent to n8n successfully!');
        }

        return back()->with(
            'error',
            'Failed to send data to n8n: ' . $response->status()
        );

    } catch (\Throwable $e) {
        return back()->with(
            'error',
            'An error occurred: ' . $e->getMessage()
        );
    }
}

}
