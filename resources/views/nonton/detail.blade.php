<x-layouts.dashboard>
    <x-slot name="header">Detail</x-slot>
    <x-slot name="title">{{ $detail['title'] ?? 'Detail' }} - Nonton</x-slot>

    <div x-data="detailApp()" class="space-y-8 -mx-6 -mt-6">
        <!-- Hero Section with Compact Info -->
        <div class="relative h-[500px] overflow-hidden">
            <!-- Backdrop Image -->
            <div class="absolute inset-0 z-0">
                <img src="{{ $detail['poster'] ?? '' }}"
                    class="w-full h-full object-cover opacity-60 blur-sm">

                <div class="absolute inset-0 bg-gradient-to-t from-[#111827] via-[#111827]/50 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-[#111827]/80 to-transparent"></div>
            </div>

            <!-- Content Container -->
            <div class="relative z-10 flex items-center h-full" style="margin-bottom: 100px; margin-top: 100px;">
                <div class="container mx-auto px-8">
                    <div class="flex flex-row md:flex-row gap-10 items-start">
                        <!-- Floating Poster (Left) -->
                        <div class="flex-shrink-0 w-64 md:w-64 relative z-10">
                            <div class="aspect-[2/3] rounded-xl overflow-hidden shadow-2xl ring-1 ring-white/10 transform rotate-1 hover:rotate-0 transition-transform duration-500">
                                <img src="{{ $detail['poster'] ?? '' }}" alt="{{ $detail['title'] ?? '' }}" class="w-full h-full object-cover">
                            </div>
                        </div>

                        <!-- Info (Right) -->
                        <div class=" pt-6 md:pt-0 p-6 mx-5">
                            <!-- Title -->
                            <h1 class="font-extrabold fw-bold my-2 text-white leading-tight font-heading drop-shadow-lg">{{ $detail['title'] ?? 'Unknown Title' }}</h1>
                            
                            <!-- Meta Data Row -->
                            <div class="flex flex-wrap items-center my-3 gap-4 text-sm font-medium text-gray-200">
                                @if(isset($detail['rating']))
                                    <span class="flex items-center gap-1 text-yellow-400">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        {{ $detail['rating'] }}
                                    </span>
                                @endif
                                
                                @if(isset($detail['year']))
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $detail['year'] }}
                                    </span>
                                @endif

                                @if(isset($detail['type']))
                                    <span class="px-2 py-0.5 rounded bg-gray-500 text-xs uppercase tracking-wide">{{ $detail['type'] === 'tv' ? 'Series' : 'Movie' }}</span>
                                @endif

                                @if(isset($detail['country']))
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4 text-gray-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $detail['country'] }}
                                    </span>
                                @endif
                            </div>

                            <!-- Genre Badges -->
                            @if(isset($detail['genre']))
                                <div class="flex flex-wrap gap-2 my-2">
                                    @foreach(explode(',', $detail['genre']) as $genre)
                                        <span class="px-3 py-1 bg-gray-700/60 backdrop-blur-sm text-gray-100 fw-bold text-sm rounded-full border border-gray-600/50">{{ trim($genre) }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Description -->
                            @if(isset($detail['description']))
                                <p class="text-gray-100 text-md fw-semibold leading-relaxed max-w-2xl line-clamp-3 md:line-clamp-none">{{ $detail['description'] }}</p>
                            @endif

                            <!-- Cast (Text Only as per Reference) -->
                            @if(isset($detail['cast']) && count($detail['cast']) > 0)
                                <div class="text-md text-gray-100 max-w-2xl">
                                    <span class="font-bold text-white">Pemeran:</span>
                                    @foreach(array_slice($detail['cast'], 0, 10) as $actor)
                                        {{ is_array($actor) ? ($actor['name'] ?? $actor['title'] ?? '') : $actor }}@if(!$loop->last), @endif
                                    @endforeach
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="pt-4">
                                <a href="{{ route('nonton.watch', $detailPath) }}"
                                    class="inline-flex items-center gap-2 px-5 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg transition-all duration-300 shadow-lg shadow-red-600/20 transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                    </svg>
                                    Tonton Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seasons & Episodes Section -->
        @if(isset($detail['seasons']) && count($detail['seasons']) > 0)
            <div class="px-6 pb-12 mt-5" x-data="{ 
                activeSeason: 0, 
                searchQuery: '',
                get episodes() {
                    const season = this.seasons[this.activeSeason];
                    if (!season || !season.episodes) return [];
                    if (!this.searchQuery) return season.episodes;
                    
                    const lowerQuery = this.searchQuery.toLowerCase();
                    return season.episodes.filter(ep => 
                        (ep.title && ep.title.toLowerCase().includes(lowerQuery)) || 
                        (ep.episode && ep.episode.toString().includes(lowerQuery))
                    );
                },
                seasons: {{ json_encode($detail['seasons'] ?? []) }}
            }">
                <div class="container mx-auto max-w-5xl">
                    <div class="flex items-center gap-2 mb-6 text-white text-xl font-bold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        Episodes
                    </div>

                    <!-- Filter Bar -->
                    <div class="flex flex-wrap gap-4 mb-8">
                        <div class="relative flex-1 max-w-xs">
                            <input 
                                x-model="searchQuery"
                                type="text" 
                                placeholder="Cari episode..." 
                                class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2 text-sm text-white focus:outline-none focus:border-red-500 transition-colors placeholder-gray-500">
                             <div class="absolute right-3 top-2.5 text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Last Episode Button -->
                        <button class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-lg transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                            </svg>
                            Episode Terakhir
                        </button>
                    </div>

                    <!-- Season Tabs -->
                    @if(count($detail['seasons']) > 1)
                        <div class="flex flex-wrap gap-2 mb-6">
                            @foreach($detail['seasons'] as $index => $season)
                                <button 
                                    @click="activeSeason = {{ $index }}"
                                    :class="activeSeason === {{ $index }} ? 'bg-red-600 text-white border-red-600' : 'bg-gray-800/50 text-gray-400 border-gray-700 hover:text-white hover:border-gray-500'"
                                    class="px-5 py-2 rounded-lg text-sm font-bold border transition-all duration-200">
                                    {{ $season['name'] ?? $season['title'] ?? 'Season ' . ($index + 1) }}
                                </button>
                            @endforeach
                        </div>
                    @endif
                    <!-- Episode Grid Box Style -->
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        <template x-for="(episode, index) in episodes" :key="index">
                            <a
                                :href="'{{ route('nonton.watch', $detailPath) }}?episode=' + encodeURIComponent(episode.playerUrl)"
                                class="text-left bg-gray-800/40 hover:bg-gray-700/60 border border-gray-800 hover:border-gray-600 rounded-lg p-4 transition-all duration-200 group block">
                                <div class="text-[10px] uppercase text-gray-500 font-semibold mb-1">
                                    Episode <span x-text="episode.episode || index + 1"></span>
                                </div>
                                <div class="text-gray-200 font-bold text-sm group-hover:text-red-400 transition-colors truncate" 
                                     x-text="'Episode ' + (episode.episode || index + 1)">
                                </div>
                            </a>
                        </template>
                        
                        <!-- Empty State -->
                        <div x-show="episodes.length === 0" class="col-span-full py-8 text-center text-gray-500">
                            Tidak ada episode ditemukan.
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Related Content -->
        @if(isset($detail['related']) && count($detail['related']) > 0)
            <div class="px-6 pb-8 container mx-auto max-w-5xl">
                <h2 class="text-xl font-bold text-white mb-6 border-l-4 border-red-600 pl-3">Konten Serupa</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">
                    @foreach($detail['related'] as $item)
                        <a href="{{ route('nonton.detail', ['detailPath' => $item['detailPath'] ?? '']) }}" class="group block relative overflow-hidden rounded-lg">
                            <div class="aspect-[2/3] bg-gray-800">
                                <img src="{{ $item['poster'] ?? '' }}" alt="{{ $item['title'] ?? '' }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" loading="lazy">
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-2">
                                <h3 class="text-white text-xs font-bold line-clamp-2">{{ $item['title'] ?? '' }}</h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
        function detailApp() {
            return {
                init() {
                    // Cleaner init without modal listeners
                }
            };
        }
    </script>
</x-layouts.dashboard>
