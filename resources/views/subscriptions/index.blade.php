<x-layouts.dashboard>
    <x-slot name="header">Langganan</x-slot>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="bg-gradient-to-r from-purple-500/20 to-violet-500/20 rounded-2xl p-4 border border-purple-500/30">
            <p class="text-sm text-gray-400">Total Per Bulan</p>
            <p class="text-2xl font-bold text-purple-400">Rp {{ number_format($totalMonthly, 0, ',', '.') }}</p>
        </div>
        <a href="{{ route('subscriptions.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-purple-500 to-violet-600 hover:from-purple-600 hover:to-violet-700 rounded-xl font-medium transition-all shadow-lg shadow-purple-500/25">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tambah Langganan
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($subscriptions as $sub)
            <div
                class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50 {{ $sub->status !== 'active' ? 'opacity-60' : '' }}">
                <div class="flex items-start justify-between mb-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500/20 to-violet-500/20 flex items-center justify-center">
                        <span class="text-lg font-bold text-purple-400">{{ substr($sub->name, 0, 2) }}</span>
                    </div>
                    <span class="px-2 py-1 rounded-lg text-xs font-medium
                        {{ $sub->status === 'active' ? 'bg-green-500/20 text-green-400' : '' }}
                        {{ $sub->status === 'paused' ? 'bg-yellow-500/20 text-yellow-400' : '' }}
                        {{ $sub->status === 'cancelled' ? 'bg-red-500/20 text-red-400' : '' }}">
                        {{ ucfirst($sub->status) }}
                    </span>
                </div>
                <h3 class="text-lg font-semibold mb-1">{{ $sub->name }}</h3>
                <p class="text-2xl font-bold text-purple-400 mb-2">Rp {{ number_format($sub->amount, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-400 mb-4">{{ ucfirst($sub->billing_cycle) }} • Next:
                    {{ $sub->next_billing_date->format('d M Y') }}</p>
                <div class="flex items-center gap-2">
                    <a href="{{ route('subscriptions.edit', $sub) }}"
                        class="flex-1 py-2 text-center bg-gray-700 hover:bg-gray-600 rounded-xl text-sm transition">Edit</a>
                    <form action="{{ route('subscriptions.destroy', $sub) }}" method="POST" class="flex-1"
                        onsubmit="return confirm('Hapus langganan ini?')">
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
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Belum ada langganan
            </div>
        @endforelse
    </div>
    @if($subscriptions->hasPages())
    <div class="mt-6">{{ $subscriptions->links() }}</div>@endif
</x-layouts.dashboard>