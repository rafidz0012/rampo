<x-layouts.dashboard>
    <x-slot name="header">To-do</x-slot>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <div class="bg-gray-800/50 rounded-xl px-4 py-2 border border-gray-700/50">
                <span class="text-gray-400">Total:</span> <span class="font-semibold">{{ $stats['total'] }}</span>
            </div>
            <div class="bg-green-500/10 rounded-xl px-4 py-2 border border-green-500/30">
                <span class="text-green-400">Selesai:</span> <span
                    class="font-semibold text-green-400">{{ $stats['completed'] }}</span>
            </div>
            @if($stats['overdue'] > 0)
                <div class="bg-red-500/10 rounded-xl px-4 py-2 border border-red-500/30">
                    <span class="text-red-400">Overdue:</span> <span
                        class="font-semibold text-red-400">{{ $stats['overdue'] }}</span>
                </div>
            @endif
        </div>
        <a href="{{ route('todos.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-cyan-500 to-teal-600 hover:from-cyan-600 hover:to-teal-700 rounded-xl font-medium transition-all shadow-lg shadow-cyan-500/25">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tambah To-do
        </a>
    </div>

    <form method="GET" class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-4 mb-6 border border-gray-700/50">
        <div class="flex flex-wrap gap-4">
            <select name="status" class="px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-xl">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
            </select>
            <select name="priority" class="px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-xl">
                <option value="">Semua Prioritas</option>
                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-xl transition">Filter</button>
        </div>
    </form>

    <div class="space-y-3">
        @forelse($todos as $todo)
            <div
                class="flex items-start gap-4 p-4 bg-gray-800/50 backdrop-blur-xl rounded-2xl border {{ $todo->isOverdue() ? 'border-red-500/50' : 'border-gray-700/50' }} {{ $todo->is_completed ? 'opacity-60' : '' }}">
                <form action="{{ route('todos.toggle', $todo) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit"
                        class="w-6 h-6 mt-0.5 rounded-full border-2 flex items-center justify-center transition
                        {{ $todo->is_completed ? 'bg-green-500 border-green-500' : 'border-gray-500 hover:border-cyan-400' }}">
                        @if($todo->is_completed)
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        @endif
                    </button>
                </form>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <h3 class="font-medium {{ $todo->is_completed ? 'line-through text-gray-400' : '' }}">
                            {{ $todo->title }}</h3>
                        <span
                            class="px-2 py-0.5 rounded text-xs font-medium
                            {{ $todo->priority === 'high' ? 'bg-red-500/20 text-red-400' : '' }}
                            {{ $todo->priority === 'medium' ? 'bg-yellow-500/20 text-yellow-400' : '' }}
                            {{ $todo->priority === 'low' ? 'bg-green-500/20 text-green-400' : '' }}">{{ ucfirst($todo->priority) }}</span>
                    </div>
                    @if($todo->description)
                        <p class="text-sm text-gray-400 mb-2">{{ Str::limit($todo->description, 100) }}</p>
                    @endif
                    <div class="flex items-center gap-4 text-xs text-gray-500">
                        @if($todo->due_date)
                            <span class="{{ $todo->isOverdue() ? 'text-red-400' : '' }}">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $todo->due_date->format('d M Y, H:i') }}
                            </span>
                        @endif
                        @if($todo->completed_at)
                            <span class="text-green-400">Selesai {{ $todo->completed_at->diffForHumans() }}</span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('todos.edit', $todo) }}" class="p-2 rounded-lg hover:bg-gray-600 transition">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                    <form action="{{ route('todos.destroy', $todo) }}" method="POST"
                        onsubmit="return confirm('Hapus to-do ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 rounded-lg hover:bg-red-500/20 transition">
                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Belum ada to-do
            </div>
        @endforelse
    </div>
    @if($todos->hasPages())
    <div class="mt-6">{{ $todos->links() }}</div>@endif
</x-layouts.dashboard>