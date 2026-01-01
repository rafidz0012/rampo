<x-layouts.dashboard>
    <x-slot name="header">Pemasukan</x-slot>

    <!-- Summary & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="bg-gradient-to-r from-green-500/20 to-emerald-500/20 rounded-2xl p-4 border border-green-500/30">
            <p class="text-sm text-gray-400">Total Bulan Ini</p>
            <p class="text-2xl font-bold text-green-400">Rp {{ number_format($totalThisMonth, 0, ',', '.') }}</p>
        </div>
        <a href="{{ route('incomes.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 rounded-xl font-medium transition-all shadow-lg shadow-green-500/25">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tambah Pemasukan
        </a>
    </div>

    <!-- Filters -->
    <form method="GET" class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-4 mb-6 border border-gray-700/50">
        <div class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pemasukan..."
                class="flex-1 min-w-[200px] px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500/50 focus:border-green-500 transition">
            <select name="category"
                class="px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500/50 focus:border-green-500 transition">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}
                    </option>
                @endforeach
            </select>
            <select name="month"
                class="px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500/50 focus:border-green-500 transition">
                <option value="">Semua Bulan</option>
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                @endfor
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
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Tanggal</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Judul</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Kategori</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-gray-400">Jumlah</th>
                        <th class="px-6 py-4 text-center text-sm font-medium text-gray-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($incomes as $income)
                        <tr class="hover:bg-gray-700/30 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $income->date->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <p class="font-medium">{{ $income->title }}</p>
                                @if($income->notes)
                                    <p class="text-sm text-gray-400 truncate max-w-xs">{{ $income->notes }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2.5 py-1 rounded-lg bg-green-500/20 text-green-400 text-sm font-medium">{{ ucfirst($income->category) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-green-400">Rp
                                {{ number_format($income->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('incomes.edit', $income) }}"
                                        class="p-2 rounded-lg hover:bg-gray-600 transition">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('incomes.destroy', $income) }}" method="POST"
                                        onsubmit="return confirm('Hapus pemasukan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg hover:bg-red-500/20 transition">
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
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                Belum ada data pemasukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($incomes->hasPages())
            <div class="px-6 py-4 border-t border-gray-700/50">
                {{ $incomes->links() }}
            </div>
        @endif
    </div>
</x-layouts.dashboard>