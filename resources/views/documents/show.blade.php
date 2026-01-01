<x-layouts.dashboard>
    <x-slot name="header">{{ $document->name }}</x-slot>

    <div class="max-w-2xl">
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50 mb-6">
            <div class="flex items-center gap-4 mb-6">
                <div
                    class="w-16 h-16 rounded-xl bg-gradient-to-br from-amber-500/20 to-orange-500/20 flex items-center justify-center">
                    <span class="text-lg font-bold text-amber-400 uppercase">{{ $document->file_type }}</span>
                </div>
                <div>
                    <h2 class="text-xl font-semibold">{{ $document->name }}</h2>
                    <p class="text-gray-400">{{ $document->getFormattedSize() }} • {{ ucfirst($document->category) }}
                    </p>
                </div>
            </div>
            @if($document->notes)
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-400 mb-2">Catatan</h3>
                    <p class="text-gray-300">{{ $document->notes }}</p>
                </div>
            @endif
            <div class="flex items-center gap-4">
                <a href="{{ route('documents.download', $document) }}"
                    class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl font-medium shadow-lg shadow-amber-500/25">
                    Download
                </a>
                <form action="{{ route('documents.destroy', $document) }}" method="POST"
                    onsubmit="return confirm('Hapus dokumen ini?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="px-6 py-3 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-xl font-medium transition">Hapus</button>
                </form>
            </div>
        </div>
        <a href="{{ route('documents.index') }}"
            class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke daftar
        </a>
    </div>
</x-layouts.dashboard>