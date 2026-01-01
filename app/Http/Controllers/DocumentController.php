<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $documents = auth()->user()->documents()
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->when($request->type, fn($q) => $q->where('file_type', $request->type))
            ->latest()
            ->paginate(15);

        $categories = ['ktp', 'sim', 'kk', 'ijazah', 'sertifikat', 'kontrak', 'tagihan', 'general'];

        $stats = [
            'total' => auth()->user()->documents()->count(),
            'total_size' => auth()->user()->documents()->sum('file_size'),
        ];

        return view('documents.index', compact('documents', 'categories', 'stats'));
    }

    public function create()
    {
        $categories = ['ktp', 'sim', 'kk', 'ijazah', 'sertifikat', 'kontrak', 'tagihan', 'general'];
        return view('documents.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|max:10240', // 10MB max
            'category' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $path = $file->store('documents', 'public');

        auth()->user()->documents()->create([
            'name' => $validated['name'],
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'category' => $validated['category'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('documents.index')
            ->with('success', 'Dokumen berhasil diupload!');
    }

    public function show(Document $document)
    {
        $this->authorize('view', $document);
        return view('documents.show', compact('document'));
    }

    public function download(Document $document)
    {
        $this->authorize('view', $document);
        return Storage::disk('public')->download($document->file_path, $document->name . '.' . $document->file_type);
    }

    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);
        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Dokumen berhasil dihapus!');
    }
}
