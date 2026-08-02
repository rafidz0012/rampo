<x-layouts.dashboard>
    <x-slot name="header">Hutang</x-slot>

    <!-- Summary & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="bg-gradient-to-r from-red-500/20 to-rose-500/20 rounded-2xl p-4 border border-red-500/30">
            <p class="text-sm text-gray-400">Total Sisa Hutang</p>
            <p class="text-2xl font-bold text-red-400">Rp {{ number_format($totalRemaining ?? $debts->sum('remaining_amount'), 0, ',', '.') }}</p>
        </div>
        <a href="{{ route('debt.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 rounded-xl font-medium transition-all shadow-lg shadow-red-500/25">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tambah Hutang
        </a>
    </div>

    <!-- Filters -->
    <form method="GET" class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-4 mb-6 border border-gray-700/50">
        <div class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau pemberi hutang..."
                class="flex-1 min-w-[200px] px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition">
            
            <select name="status"
                class="px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Belum Dibayar</option>
                <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Dicicil</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-xl transition">Filter</button>
        </div>
    </form>

    <!-- Table -->
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-700/30">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Judul & Pemberi Hutang</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Jatuh Tempo</th>
                        <th class="px-6 py-4 text-center text-sm font-medium text-gray-400">Status</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-gray-400">Total</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-gray-400">Sisa Hutang</th>
                        <th class="px-6 py-4 text-center text-sm font-medium text-gray-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($debts as $debt)
                        <tr class="hover:bg-gray-700/30 transition">
                            <td class="px-6 py-4">
                                <p class="font-medium text-white">{{ $debt->title }}</p>
                                <p class="text-sm text-gray-400">Pemberi: {{ $debt->creditor_name }}</p>
                                @if($debt->note)
                                    <p class="text-xs text-gray-500 truncate max-w-xs mt-0.5">{{ $debt->note }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ $debt->due_date ? $debt->due_date->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($debt->status === 'paid')
                                    <span class="px-2.5 py-1 rounded-lg bg-green-500/20 text-green-400 text-xs font-medium">Lunas</span>
                                @elseif($debt->status === 'partial')
                                    <span class="px-2.5 py-1 rounded-lg bg-yellow-500/20 text-yellow-400 text-xs font-medium">Dicicil</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg bg-red-500/20 text-red-400 text-xs font-medium">Belum Dibayar</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-medium text-gray-300">
                                Rp {{ number_format($debt->total, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-red-400">
                                Rp {{ number_format($debt->remaining_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('debt.edit', $debt) }}"
                                        class="p-2 rounded-lg hover:bg-gray-600 transition" title="Edit">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('debt.destroy', $debt) }}" method="POST"
                                        onsubmit="return confirm('Hapus data hutang ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg hover:bg-red-500/20 transition" title="Hapus">
                                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                Belum ada data hutang
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($debts->hasPages())
            <div class="px-6 py-4 border-t border-gray-700/50">
                {{ $debts->links() }}
            </div>
        @endif
    </div>
</x-layouts.dashboard>