<x-layouts.dashboard>
    <x-slot name="header">Nonton</x-slot>
    <x-slot name="title">{{ $detail['title'] ?? 'Watch' }} - Nonton</x-slot>

    <div x-data="watchApp()" class="space-y-6 -mx-6 -mt-6">
        <!-- Video Player Section (Full Width) -->
        <div class="relative w-full bg-black aspect-video md:aspect-[21/9] lg:h-[70vh] flex items-center justify-center group">
            @if($activeEpisodeUrl)
                <iframe
                    src="{{ $activeEpisodeUrl }}"
                    class="w-full h-full"
                    frameborder="0"
                    allowfullscreen
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                </iframe>
            @else
                <div class="text-center text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-lg">Video tidak tersedia</p>
                </div>
            @endif
            
            <a href="{{ route('nonton.detail', $detailPath) }}" class="absolute top-4 left-4 p-2 bg-black/50 hover:bg-black/80 text-white rounded-full transition-colors opacity-0 group-hover:opacity-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
        </div>

        <div class="container mx-auto px-6 max-w-5xl space-y-8">
            <!-- Info Section -->
            <div class="space-y-4">
                <h1 class="text-3xl font-bold text-white">{{ $detail['title'] ?? 'Unknown Title' }}</h1>
                
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-400">
                    @if(isset($detail['year']))
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $detail['year'] }}
                        </span>
                    @endif
                    @if(isset($detail['rating']))
                        <span class="flex items-center gap-1 text-yellow-400">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            {{ $detail['rating'] }}
                        </span>
                    @endif
                    @if(isset($detail['country']))
                        <span class="flex items-center gap-1">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $detail['country'] }}
                        </span>
                    @endif
                </div>

                @if(isset($detail['description']))
                    <p class="text-gray-400 text-sm leading-relaxed max-w-4xl">{{ $detail['description'] }}</p>
                @endif
            </div>

            <!-- Episodes Section -->
            @if(isset($detail['seasons']) && count($detail['seasons']) > 0)
                <div class="pt-6 border-t border-gray-800" x-data="{ 
                    activeSeason: 0, 
                    searchQuery: '',
                    get episodes() {
                        const season = this.seasons[this.activeSeason];
                        if (!season || !season.episodes) return [];
                        if (!this.searchQuery) return season.episodes;
                        
                        const lowerQuery = this.searchQuery.toLowerCase();
                        return season.episodes.filter(ep => 
                            (ep.title && ep.title.toLowerCase().includes(lowerQuery)) || 
                            (ep.number && ep.number.toString().includes(lowerQuery))
                        );
                    },
                    seasons: @json($detail['seasons'] ?? [])
                }">
                    <div class="flex items-center gap-2 mb-6 text-white text-xl font-bold border-l-4 border-red-600 pl-3">
                        Episodes
                    </div>

                    <!-- Filter Bar -->
                    <div class="flex flex-wrap gap-4 mb-6">
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

                    <!-- Episode Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                        <template x-for="(episode, index) in episodes" :key="index">
                            <a
                                :href="'{{ route('nonton.watch', $detailPath) }}?episode=' + encodeURIComponent(episode.playerUrl)"
                                :class="episode.playerUrl === '{{ $activeEpisodeUrl }}' ? 'bg-red-600/20 border-red-600/50 ring-1 ring-red-600' : 'bg-gray-800/40 hover:bg-gray-700/60 border-gray-800 hover:border-gray-600'"
                                class="text-left border rounded-lg p-4 transition-all duration-200 group block relative overflow-hidden">
                                
                                <div class="text-[10px] uppercase text-gray-500 font-semibold mb-1 group-hover:text-gray-400">
                                    Episode <span x-text="episode.number || episode.episode || index + 1"></span>
                                </div>
                                <div class="text-gray-200 font-bold text-sm group-hover:text-red-400 transition-colors truncate" 
                                     x-text="'Episode ' + (episode.number || episode.episode || index + 1)">
                                </div>
                                
                                <!-- Playing Indicator -->
                                <template x-if="episode.playerUrl === '{{ $activeEpisodeUrl }}'">
                                    <div class="absolute top-2 right-2">
                                        <span class="flex h-2 w-2 relative">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                        </span>
                                    </div>
                                </template>
                            </a>
                        </template>
                         <div x-show="episodes.length === 0" class="col-span-full py-8 text-center text-gray-500">
                            Tidak ada episode ditemukan.
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function watchApp() {
            return {
                // Future expansion
            };
        }
    </script>
</x-layouts.dashboard>
