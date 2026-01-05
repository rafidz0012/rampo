<x-layouts.dashboard>
    <x-slot name="header">N8n Clipper</x-slot>

    <div class="max-w-3xl mx-auto">
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

        @if($candidates->count() > 0)
            <div class="mt-8 bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50">
                <h3 class="text-xl font-semibold text-gray-100 mb-6">Clip Candidates</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-gray-300">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="pb-3 px-4">Video</th>
                                <th class="pb-3 px-4">Range</th>
                                <th class="pb-3 px-4">Duration</th>
                                <th class="pb-3 px-4">Score</th>
                                <th class="pb-3 px-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($candidates as $candidate)
                                <tr class="hover:bg-gray-700/30 transition-colors">
                                    <td class="py-4 px-4">
                                        <a href="{{ $candidate->video->youtube_url }}" target="_blank" class="text-blue-400 hover:text-blue-300 hover:underline">
                                            {{ Str::limit($candidate->video->youtube_url, 40) }}
                                        </a>
                                    </td>
                                    <td class="py-4 px-4 space-x-2">
                                        <span class="bg-gray-700 px-2 py-1 rounded text-sm">{{ $candidate->start_seconds }}s</span>
                                        <span class="text-gray-500">to</span>
                                        <span class="bg-gray-700 px-2 py-1 rounded text-sm">{{ $candidate->end_seconds }}s</span>
                                    </td>
                                    <td class="py-4 px-4">{{ $candidate->duration }}s</td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-16 h-1.5 bg-gray-700 rounded-full overflow-hidden">
                                                <div class="h-full bg-blue-500 rounded-full" style="width: {{ min($candidate->score, 100) }}%"></div>
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        </div>
    </div>
</x-layouts.dashboard>
