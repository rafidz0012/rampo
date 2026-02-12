<x-layouts.dashboard>
    <x-slot name="header">Hasil Pencarian</x-slot>
    <x-slot name="title">Cari: {{ $query }} - Nonton</x-slot>

    <div class="space-y-6">
        <!-- Search Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">Hasil Pencarian</h1>
                <p class="text-gray-400 mt-1">Menampilkan hasil untuk "<span class="text-white">{{ $query }}</span>"</p>
            </div>
            <a href="{{ route('nonton.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700/50 hover:bg-gray-600 text-white rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>

        <!-- Search Form -->
        <form action="{{ route('nonton.search') }}" method="GET" class="max-w-2xl">
            <div class="relative">
                <input
                    type="text"
                    name="q"
                    value="{{ $query }}"
                    placeholder="Cari film, drama, anime..."
                    class="w-full px-5 py-4 pl-14 bg-gray-800/80 border border-gray-700/50 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all">
                <svg class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition">
                    Cari
                </button>
            </div>
        </form>

        <!-- Results -->
        @if(count($results) > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @foreach($results as $item)
                    <a href="{{ route('nonton.detail', ['detailPath' => $item['detailPath'] ?? '']) }}" class="group">
                        <div class="relative aspect-[2/3] rounded-xl overflow-hidden bg-gray-800/50">
                            <img src="{{ $item['poster'] ?? '' }}" alt="{{ $item['title'] ?? '' }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="flex items-center gap-1 text-yellow-400">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        {{ $item['rating'] ?? 'N/A' }}
                                    </span>
                                    <span class="text-gray-400">{{ $item['year'] ?? '' }}</span>
                                </div>
                            </div>
                            <!-- Play Button Overlay -->
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="w-14 h-14 rounded-full bg-red-600/90 flex items-center justify-center transform scale-0 group-hover:scale-100 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-white ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <!-- Type Badge -->
                            <div class="absolute top-2 left-2">
                                <span class="{{ ($item['type'] ?? '') === 'tv' ? 'bg-blue-600' : 'bg-red-600' }} px-2 py-1 text-xs font-medium text-white rounded">
                                    {{ ($item['type'] ?? '') === 'tv' ? 'Series' : 'Movie' }}
                                </span>
                            </div>
                        </div>
                        <h3 class="mt-3 text-sm font-medium text-white group-hover:text-red-400 transition-colors line-clamp-2">{{ $item['title'] ?? '' }}</h3>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <svg class="w-20 h-20 mx-auto text-gray-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h2 class="text-xl font-semibold text-white mb-2">Tidak ada hasil</h2>
                <p class="text-gray-400 max-w-md mx-auto">Tidak ditemukan konten yang cocok dengan pencarian "{{ $query }}". Coba kata kunci lain.</p>
            </div>
        @endif
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-layouts.dashboard>
