<x-layouts.dashboard>
    <x-slot name="header">Clipper</x-slot>

    <div class="max-w-3xl mx-auto" x-data="{ tab: 'form', selectedClip: null }">
        <!-- Tabs Navigation -->
        <div class="flex space-x-1 p-1 bg-gray-800/50 backdrop-blur-xl rounded-xl border border-gray-700/50 mb-6 w-fit">
            <button 
                @click="tab = 'form'" 
                :class="{ 'bg-gray-700 text-white shadow': tab === 'form', 'text-gray-400 hover:text-gray-200': tab !== 'form' }" 
                class="px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200">
                Form
            </button>
            <button 
                @click="tab = 'candidate'" 
                :class="{ 'bg-gray-700 text-white shadow': tab === 'candidate', 'text-gray-400 hover:text-gray-200': tab !== 'candidate' }" 
                class="px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200">
                Candidate
            </button>
            <button 
                @click="tab = 'result'" 
                :class="{ 'bg-gray-700 text-white shadow': tab === 'result', 'text-gray-400 hover:text-gray-200': tab !== 'result' }" 
                class="px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200">
                Result
            </button>
        </div>

        <!-- form -->
        <div x-show="tab === 'form'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50">
                @if (session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 flex items-center gap-3">
                         <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('clipper.analyze') }}" class="space-y-6">
                    @csrf
    
                    <div>
                        <input type="text" name="url" placeholder="YouTube URL" required class="block mt-1 w-full bg-gray-700/50 border-gray-600 text-gray-100 focus:ring-blue-500 focus:border-blue-500 rounded-md shadow-sm">
                    </div>
    
                    <div class="flex items-center justify-end pt-4">
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-900 transition-all duration-200">
                            Analyze
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- candidate -->
        <div x-show="tab === 'candidate'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
            @if(isset($candidates) && $candidates->count() > 0)
                <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50">
                    <h3 class="text-xl font-semibold text-gray-100 mb-6">Clip Candidates</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-gray-300">
                            <thead>
                                <tr class="border-b border-gray-700">
                                    <th class="pb-3 px-4">Source</th>
                                    <th class="pb-3 px-4">Start</th>
                                    <th class="pb-3 px-4">End</th>
                                    <th class="pb-3 px-4">Duration</th>
                                    <th class="pb-3 px-4">Score</th>
                                    <th class="pb-3 px-4">Status</th>
                                    <th class="pb-3 px-4">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach($candidates as $candidate)
                                    <tr class="hover:bg-gray-700/30 transition-colors">
                                        <td class="py-4 px-4 space-x-2">
                                            <span class="bg-gray-700 px-2 py-1 rounded text-sm">{{ $candidate->video->youtube_url }}</span>    
                                        </td>
                                        <td class="py-4 px-4 space-x-2">
                                            <span class="bg-gray-700 px-2 py-1 rounded text-sm">{{ $candidate->start_time_formatted }}</span>    
                                        </td>
                                        <td class="py-4 px-4 space-x-2">
                                            <span class="bg-gray-700 px-2 py-1 rounded text-sm">{{ $candidate->end_time_formatted }}</span>
                                        </td>
                                        <td class="py-4 px-4">{{ $candidate->duration }}s</td>
                                        <td class="py-4 px-4">
                                            <div class="flex items-center gap-2">
                                                <div style="width:64px;height:6px;background:#374151;">
                                                    <div style="width:{{ $candidate->score }}%;height:100%;background:#3b82f6;"></div>
                                                </div>
    
                                                <span class="text-sm">{{ $candidate->score }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium 
                                                {{ $candidate->status === 'processed' ? 'bg-green-500/10 text-green-400' : 
                                                   ($candidate->status === 'pending' ? 'bg-yellow-500/10 text-yellow-400' : 'bg-gray-700 text-gray-400') }}">
                                                {{ ucfirst($candidate->status) }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <form method="POST" action="{{ route('clip.process', $candidate->id) }}">
                                                @csrf
                                                <button
                                                    class="px-3 py-1 text-xs rounded bg-blue-600 hover:bg-blue-700">
                                                    Clip
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="text-center py-12 text-gray-400 bg-gray-800/50 rounded-2xl border border-gray-700/50">
                    No candidates found.
                </div>
            @endif
        </div>

        <!-- result -->
        <div x-show="tab === 'result'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
            @if(isset($clips) && $clips->count() > 0)
                <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50">
                    <h3 class="text-xl font-semibold text-gray-100 mb-6">Generated Clips</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-gray-300">
                            <thead>
                                <tr class="border-b border-gray-700">
                                    <th class="pb-3 px-4">ID</th>
                                    <th class="pb-3 px-4">Source Time</th>
                                    <th class="pb-3 px-4">Status</th>
                                    <th class="pb-3 px-4">Output</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach($clips as $clip)
                                    <tr class="hover:bg-gray-700/30 transition-colors">
                                        <td class="py-4 px-4">
                                            #{{ $clip->id }}
                                        </td>
                                        <td class="py-4 px-4">
                                            @if($clip->candidate)
                                                <span class="text-sm text-gray-400">{{ $clip->candidate->start_time_formatted }} - {{ $clip->candidate->end_time_formatted }}</span>
                                            @else
                                                <span class="text-gray-500">-</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4">
                                             <span class="px-2 py-1 rounded-full text-xs font-medium 
                                                {{ $clip->status === 'done' ? 'bg-green-500/10 text-green-400' : 
                                                   ($clip->status === 'processing' ? 'bg-blue-500/10 text-blue-400' : 'bg-gray-700 text-gray-400') }}">
                                                {{ ucfirst($clip->status) }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 font-mono text-xs text-gray-400">
                                            @if($clip->output_path)
                                                <div class="flex items-center gap-2">
                                                    <button 
                                                        @click="selectedClip = '{{ asset($clip->output_path) }}'"
                                                        class="flex items-center gap-2 px-3 py-1.5 bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 rounded-lg transition-colors text-xs font-medium">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Watch
                                                    </button>
                                                    <a href="{{ asset($clip->output_path) }}" download
                                                        class="flex items-center gap-2 px-3 py-1.5 bg-green-500/10 text-green-400 hover:bg-green-500/20 rounded-lg transition-colors text-xs font-medium">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                        </svg>
                                                        Download
                                                    </a>
                                                </div>
                                            @else
                                                <span class="text-gray-500">Belum ada video</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
             @else
                <div class="text-center py-12 text-gray-400 bg-gray-800/50 rounded-2xl border border-gray-700/50">
                    No clips generated yet.
                </div>
            @endif
        </div>
        
        <!-- Video Modal -->
        <div 
            x-show="selectedClip" 
            style="display: none;"
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="modal-title" 
            role="dialog" 
            aria-modal="true"
        >
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div 
                    x-show="selectedClip" 
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" 
                    @click="selectedClip = null" 
                    aria-hidden="true"
                ></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div 
                    x-show="selectedClip" 
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative inline-block align-bottom bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-700"
                >
                    <div class="absolute top-0 right-0 pt-4 pr-4 z-10">
                        <button 
                            @click="selectedClip = null"
                            type="button" 
                            class="bg-gray-800 rounded-md text-gray-400 hover:text-gray-200 focus:outline-none"
                        >
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="p-1">
                        <template x-if="selectedClip">
                            <video controls class="w-full rounded-xl" autoplay>
                                <source :src="selectedClip" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </template>
                    </div>
                </div>
            </div>
        </div>
</x-layouts.dashboard>
