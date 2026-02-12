<x-layouts.dashboard>
    <x-slot name="header">Nonton</x-slot>
    <x-slot name="title">Nonton</x-slot>

    <div x-data="nontonApp()" x-init="init()" class="space-y-8 -mx-6 -mt-6">
        <!-- Hero Banner -->
        <div class="relative h-[320px] overflow-hidden" x-show="heroItem">
            <!-- Backdrop Image -->
            <div class="absolute inset-0">
                <img :src="heroItem?.poster" :alt="heroItem?.title" class="w-full h-full object-cover object-top">
                <div class="absolute inset-0 bg-gradient-to-r from-gray-900 via-gray-900/80 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-gray-900/30"></div>
            </div>

            <!-- Content -->
            <div class="absolute inset-0 flex items-center">
                <div class="container mx-auto px-8">
                    <div class="max-w-xl space-y-4">
                        <!-- Category Badge -->
                        <span class="inline-flex items-center px-3 py-1 rounded text-xs font-bold bg-red-600 text-white uppercase tracking-wide">
                            <span x-text="heroItem?.genre?.split(',')[0] || heroItem?.type || 'Featured'"></span>
                        </span>

                        <!-- Title -->
                        <h1 class="text-4xl md:text-5xl font-bold text-white leading-tight" x-text="heroItem?.title"></h1>

                        <!-- Meta Info -->
                        <div class="flex items-center gap-3 text-sm">
                            <span class="flex items-center gap-1 text-yellow-400 font-semibold">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span x-text="heroItem?.rating || 'N/A'"></span>
                            </span>
                            <span class="text-gray-400" x-text="heroItem?.year"></span>
                            <span class="px-2 py-0.5 rounded text-xs bg-gray-700 text-gray-300" x-text="heroItem?.type === 'tv' ? 'SERIES' : 'FILM'"></span>
                        </div>

                        <!-- Description -->
                        <p class="text-gray-300 text-sm leading-relaxed line-clamp-3" x-text="heroItem?.description || ''"></p>

                        <!-- Buttons -->
                        <div class="flex gap-3 pt-2">
                            <a :href="'/nonton/detail/' + encodeURIComponent(heroItem?.detailPath || '')"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-white hover:bg-gray-100 text-gray-900 font-semibold rounded-lg transition-all duration-300">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                </svg>
                                Tonton Sekarang
                            </a>
                            <a :href="'/nonton/detail/' + encodeURIComponent(heroItem?.detailPath || '')"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-gray-700/80 hover:bg-gray-600 text-white font-semibold rounded-lg transition-all duration-300 backdrop-blur-sm border border-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Info Lengkap
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hero Navigation Dots -->
            <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex gap-2">
                <template x-for="(item, index) in trending.slice(0, 5)" :key="'hero-' + index">
                    <button
                        @click="currentHero = index; heroItem = trending[index]"
                        :class="currentHero === index ? 'w-8 bg-red-600' : 'w-2 bg-gray-500/50 hover:bg-gray-400'"
                        class="h-2 rounded-full transition-all duration-300">
                    </button>
                </template>
            </div>
        </div>

        <!-- Category Rows -->
        @foreach($categoryData as $categoryKey => $data)
            @if(count($data['items']) > 0)
                <div class="px-6">
                    <!-- Section Header -->
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="flex items-center gap-2 text-lg font-bold text-white">
                            @if($categoryKey === 'kdrama')
                                <span class="text-red-500">❤️</span>
                            @elseif($categoryKey === 'anime')
                                <span class="text-yellow-400">⭐</span>
                            @elseif($categoryKey === 'western-tv')
                                <span class="text-red-500">📺</span>
                            @elseif($categoryKey === 'indo-dub')
                                <span class="text-red-500">🎬</span>
                            @else
                                <span class="text-red-500">🎬</span>
                            @endif
                            {{ $data['label'] }}
                        </h2>
                        <a href="{{ route('nonton.category', $categoryKey) }}" class="flex items-center gap-1 text-sm text-gray-400 hover:text-red-400 transition">
                            Lihat Semua
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                    <!-- Horizontal Scroll Container -->
                    <div class="relative group">
                        <div class="flex gap-3 overflow-x-auto pb-4 scrollbar-hide scroll-smooth" id="scroll-{{ $categoryKey }}">
                            @foreach($data['items'] as $item)
                                <a href="{{ route('nonton.detail', ['detailPath' => $item['detailPath'] ?? '']) }}"
                                   class="flex-shrink-0 group/card relative overflow-hidden rounded-lg bg-gray-800 shadow-lg" 
                                   style="width: 160px; height: 240px;">
                                    
                                    <!-- Poster Image -->
                                    <img src="{{ $item['poster'] ?? '' }}"
                                         alt="{{ $item['title'] ?? '' }}"
                                         class="w-full h-full object-cover transition-transform duration-500 group-hover/card:scale-110"
                                         loading="lazy">
                                    
                                    <!-- Hover Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/80 to-transparent opacity-0 group-hover/card:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4 z-10">
                                        <!-- Play Button -->
                                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 transform scale-50 group-hover/card:scale-100 transition-transform duration-300">
                                            <div class="w-12 h-12 rounded-full bg-red-600 flex items-center justify-center shadow-lg shadow-red-600/50">
                                                <svg class="w-5 h-5 text-white ml-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Info -->
                                        <div class="transform translate-y-4 group-hover/card:translate-y-0 transition-transform duration-300">
                                            <h3 class="text-white text-sm font-bold leading-tight mb-1 line-clamp-2 drop-shadow-md">{{ $item['title'] ?? '' }}</h3>
                                            
                                            <div class="flex items-center gap-2 text-xs font-medium">
                                                @if(isset($item['rating']))
                                                    <span class="flex items-center gap-1 text-yellow-400">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                        {{ $item['rating'] }}
                                                    </span>
                                                @endif
                                                @if(isset($item['year']))
                                                    <span class="text-gray-300">{{ $item['year'] }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Default Badge (when not hovering) -->
                                    <!-- <div class="absolute top-2 right-2 bg-black/60 backdrop-blur-sm px-2 py-0.5 rounded text-[10px] text-white font-medium group-hover/card:opacity-0 transition-opacity duration-300">
                                        {{ $item['quality'] ?? 'HD' }}
                                    </div> -->
                                </a>
                            @endforeach
                        </div>

                        <!-- Scroll Buttons -->
                        <button onclick="document.getElementById('scroll-{{ $categoryKey }}').scrollBy({left: -300, behavior: 'smooth'})"
                                class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-2 w-10 h-10 rounded-full bg-black/70 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-10 hover:bg-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button onclick="document.getElementById('scroll-{{ $categoryKey }}').scrollBy({left: 300, behavior: 'smooth'})"
                                class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-2 w-10 h-10 rounded-full bg-black/70 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-10 hover:bg-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
        @endforeach

        <!-- Footer -->
        <div class="px-6 py-8 text-center border-t border-gray-800">
            <p class="text-gray-500 text-sm">© {{ date('Y') }} Rampo. Streaming Film & Series Gratis.</p>
        </div>
    </div>

    <script>
        function nontonApp() {
            return {
                trending: @json($trending ?? []),
                categoryData: @json($categoryData ?? []),
                heroItem: null,
                currentHero: 0,
                heroInterval: null,

                init() {
                    // Set initial hero item
                    if (this.trending.length > 0) {
                        this.heroItem = this.trending[0];
                    }

                    // Auto-rotate hero banner
                    this.heroInterval = setInterval(() => {
                        if (this.trending.length > 0) {
                            this.currentHero = (this.currentHero + 1) % Math.min(this.trending.length, 5);
                            this.heroItem = this.trending[this.currentHero];
                        }
                    }, 6000);
                }
            };
        }
    </script>

    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-layouts.dashboard>
