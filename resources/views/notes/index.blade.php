<x-layouts.dashboard>
    <x-slot name="header">Catatan</x-slot>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <form method="GET" class="flex-1 max-w-md">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari catatan..."
                class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500/50 transition">
        </form>
        <a href="{{ route('notes.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 rounded-xl font-medium transition-all shadow-lg shadow-blue-500/25">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Buat Catatan
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($notes as $note)
            <a href="{{ route('notes.show', $note) }}"
                class="group bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50 hover:border-blue-500/50 transition-all">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-4 h-4 rounded-full" style="background-color: {{ $note->color }}"></div>
                    @if($note->is_pinned)
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M5 5a2 2 0 012-2h6a2 2 0 012 2v2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2v4a2 2 0 01-2 2H7a2 2 0 01-2-2v-4H3a2 2 0 01-2-2V9a2 2 0 012-2h2V5z" />
                        </svg>
                    @endif
                </div>
                <h3 class="font-semibold text-lg mb-2 group-hover:text-blue-400 transition">{{ $note->title }}</h3>
                <p class="text-sm text-gray-400 line-clamp-3">{{ Str::limit(strip_tags($note->content), 120) }}</p>
                <p class="text-xs text-gray-500 mt-4">{{ $note->updated_at->diffForHumans() }}</p>
            </a>
        @empty
            <div class="col-span-full text-center py-12 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Belum ada catatan
            </div>
        @endforelse
    </div>
    @if($notes->hasPages())
    <div class="mt-6">{{ $notes->links() }}</div>@endif
</x-layouts.dashboard>