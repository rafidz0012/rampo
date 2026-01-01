<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $notes = auth()->user()->notes()
            ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%")
                ->orWhere('content', 'like', "%{$request->search}%"))
            ->orderByDesc('is_pinned')
            ->latest()
            ->paginate(12);

        return view('notes.index', compact('notes'));
    }

    public function create()
    {
        return view('notes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_pinned' => 'boolean',
            'color' => 'required|string|max:20',
        ]);

        $validated['is_pinned'] = $request->boolean('is_pinned');

        auth()->user()->notes()->create($validated);

        return redirect()->route('notes.index')
            ->with('success', 'Catatan berhasil dibuat!');
    }

    public function show(Note $note)
    {
        $this->authorize('view', $note);
        return view('notes.show', compact('note'));
    }

    public function edit(Note $note)
    {
        $this->authorize('update', $note);
        return view('notes.edit', compact('note'));
    }

    public function update(Request $request, Note $note)
    {
        $this->authorize('update', $note);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_pinned' => 'boolean',
            'color' => 'required|string|max:20',
        ]);

        $validated['is_pinned'] = $request->boolean('is_pinned');

        $note->update($validated);

        return redirect()->route('notes.index')
            ->with('success', 'Catatan berhasil diperbarui!');
    }

    public function destroy(Note $note)
    {
        $this->authorize('delete', $note);
        $note->delete();

        return redirect()->route('notes.index')
            ->with('success', 'Catatan berhasil dihapus!');
    }
}
