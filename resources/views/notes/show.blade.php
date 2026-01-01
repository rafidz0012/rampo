<x-layouts.dashboard>
    <x-slot name="header">{{ $note->title }}</x-slot>

    <div class="max-w-3xl">
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50 mb-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-4 h-4 rounded-full" style="background-color: {{ $note->color }}"></div>
                    @if($note->is_pinned)
                        <span
                            class="px-2 py-1 rounded-lg bg-yellow-500/20 text-yellow-400 text-xs font-medium">Pinned</span>
                    @endif
                    <span class="text-sm text-gray-400">{{ $note->updated_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('notes.edit', $note) }}"
                        class="p-2 rounded-lg bg-gray-700 hover:bg-gray-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                    <form action="{{ route('notes.destroy', $note) }}" method="POST"
                        onsubmit="return confirm('Hapus catatan ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 rounded-lg bg-red-500/20 hover:bg-red-500/30 transition">
                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            <div class="prose prose-invert max-w-none">
                {!! nl2br(e($note->content)) !!}
            </div>
        </div>
        <a href="{{ route('notes.index') }}"
            class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke daftar
        </a>
    </div>
</x-layouts.dashboard>