<x-layouts.dashboard>
    <x-slot name="header">Dokumen</x-slot>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <div class="bg-gray-800/50 rounded-xl px-4 py-2 border border-gray-700/50">
                <span class="text-gray-400">Total:</span> <span class="font-semibold">{{ $stats['total'] }} files</span>
            </div>
            <div class="bg-gray-800/50 rounded-xl px-4 py-2 border border-gray-700/50">
                <span class="text-gray-400">Ukuran:</span> <span
                    class="font-semibold">{{ number_format($stats['total_size'] / 1024 / 1024, 2) }} MB</span>
            </div>
        </div>
        <a href="{{ route('documents.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 rounded-xl font-medium transition-all shadow-lg shadow-amber-500/25">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
            </svg>
            Upload Dokumen
        </a>
    </div>

    <form method="GET" class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-4 mb-6 border border-gray-700/50">
        <div class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari dokumen..."
                class="flex-1 min-w-[200px] px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-xl">
            <select name="category" class="px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-xl">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-xl transition">Filter</button>
        </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($documents as $doc)
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50">
                <div class="flex items-start gap-4 mb-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500/20 to-orange-500/20 flex items-center justify-center">
                        <span class="text-sm font-bold text-amber-400 uppercase">{{ $doc->file_type }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-medium truncate">{{ $doc->name }}</h3>
                        <p class="text-sm text-gray-400">{{ $doc->getFormattedSize() }} • {{ ucfirst($doc->category) }}</p>
                    </div>
                </div>
                @if($doc->notes)
                <p class="text-sm text-gray-400 mb-4 line-clamp-2">{{ $doc->notes }}</p>@endif
                <div class="flex items-center gap-2">
                    <a href="{{ route('documents.download', $doc) }}"
                        class="flex-1 py-2 text-center bg-amber-500/20 hover:bg-amber-500/30 text-amber-400 rounded-xl text-sm transition">Download</a>
                    <form action="{{ route('documents.destroy', $doc) }}" method="POST" class="flex-1"
                        onsubmit="return confirm('Hapus dokumen ini?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="w-full py-2 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-xl text-sm transition">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg>
                Belum ada dokumen
            </div>
        @endforelse
    </div>
    @if($documents->hasPages())
    <div class="mt-6">{{ $documents->links() }}</div>@endif
</x-layouts.dashboard>