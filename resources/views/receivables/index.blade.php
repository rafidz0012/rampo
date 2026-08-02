<x-layouts.dashboard>
    <x-slot name="header">Piutang</x-slot>

    <!-- Summary & Chart Carousel / Slider -->
    <div x-data="{ activeTab: 'summary' }" class="mb-6 bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 p-5 shadow-xl">
        <!-- Header & Switcher Button -->
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-700/50">
            <div class="flex items-center gap-2">
                <button @click="activeTab = 'summary'" 
                        :class="activeTab === 'summary' ? 'bg-blue-500/20 text-blue-400 border-blue-500/30' : 'text-gray-400 hover:text-gray-200 border-transparent'"
                        class="px-3 py-1.5 rounded-xl text-sm font-medium border transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Ringkasan
                </button>
                <button @click="activeTab = 'chart'" 
                        :class="activeTab === 'chart' ? 'bg-blue-500/20 text-blue-400 border-blue-500/30' : 'text-gray-400 hover:text-gray-200 border-transparent'"
                        class="px-3 py-1.5 rounded-xl text-sm font-medium border transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                    Grafik
                </button>
            </div>

            <a href="{{ route('receivables.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 rounded-xl text-sm font-medium transition-all shadow-lg shadow-blue-500/25">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Tambah Piutang
            </a>
        </div>

        <!-- Slide 1: Ringkasan Total -->
        <div x-show="activeTab === 'summary'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 -translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gradient-to-r from-amber-500/20 to-yellow-500/20 rounded-2xl p-4 border border-amber-500/30">
                <p class="text-sm text-gray-400">Sisa Belum Lunas</p>
                <p class="text-2xl font-bold text-amber-400">Rp {{ number_format($totalRemaining, 0, ',', '.') }}</p>
            </div>
            <div class="bg-gray-700/30 rounded-2xl p-4 border border-gray-700/50">
                <p class="text-sm text-gray-400">Total Sudah Lunas</p>
                <p class="text-2xl font-bold text-green-400">Rp {{ number_format($totalPaid ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="bg-gray-700/30 rounded-2xl p-4 border border-gray-700/50">
                <p class="text-sm text-gray-400">Total Akumulasi Piutang</p>
                <p class="text-2xl font-bold text-white">Rp {{ number_format($totalAllReceivables ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Slide 2: Grafik Tren Piutang -->
        <div x-show="activeTab === 'chart'" x-cloak x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
            <div class="h-48 w-full">
                <canvas id="receivableChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <form method="GET" class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-4 mb-6 border border-gray-700/50">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <!-- Search Input -->
            <div class="md:col-span-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau nama peminjam..."
                    class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition text-gray-200 placeholder-gray-400">
            </div>
            
            <!-- Status Dropdown -->
            <div class="md:col-span-3">
                <select name="status"
                    class="w-full px-4 py-2 bg-gray-700/50 border border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition text-gray-200">
                    <option value="" class="bg-gray-800 text-gray-200">Semua Status</option>
                    <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }} class="bg-gray-800 text-gray-200">Belum Lunas</option>
                    <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }} class="bg-gray-800 text-gray-200">Dicicil</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }} class="bg-gray-800 text-gray-200">Lunas</option>
                </select>
            </div>

            <!-- Grouping: Bulan + Tahun + Tombol Filter/Reset -->
            <div class="md:col-span-5 flex items-center gap-1.5 bg-gray-700/50 p-1 rounded-xl border border-gray-600">
                <!-- Select Bulan -->
                <select name="month"
                    class="w-full px-2.5 py-1.5 bg-gray-800 text-gray-200 border-none text-sm focus:outline-none focus:ring-0 cursor-pointer rounded-lg">
                    <option value="" class="bg-gray-800 text-gray-200">Bulan</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }} class="bg-gray-800 text-gray-200">
                            {{ DateTime::createFromFormat('!m', $i)->format('M') }}
                        </option>
                    @endfor
                </select>

                <span class="text-gray-500 font-bold">|</span>

                <!-- Select Tahun -->
                <select name="year"
                    class="w-full px-2.5 py-1.5 bg-gray-800 text-gray-200 border-none text-sm focus:outline-none focus:ring-0 cursor-pointer rounded-lg">
                    <option value="" class="bg-gray-800 text-gray-200">Tahun</option>
                    @foreach(range(date('Y'), date('Y') - 4) as $y)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }} class="bg-gray-800 text-gray-200">
                            {{ $y }}
                        </option>
                    @endforeach
                </select>

                <!-- Tombol Filter -->
                <button type="submit" 
                    class="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 text-white font-medium rounded-lg text-sm transition flex items-center gap-1 shrink-0 shadow-md shadow-blue-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span>Filter</span>
                </button>

                <!-- Tombol Reset -->
                @if(request()->hasAny(['search', 'status', 'month', 'year']))
                    <a href="{{ route('receivables.index') }}" 
                        title="Reset Filter"
                        class="p-1.5 bg-gray-600/50 hover:bg-red-500/20 text-gray-300 hover:text-red-400 rounded-lg text-sm transition shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </form>

    <!-- Table Section -->
    <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl border border-gray-700/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-700/30">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Peminjam / Judul</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Status</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-gray-400">Total Piutang</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-gray-400">Sisa Tagihan</th>
                        <th class="px-6 py-4 text-center text-sm font-medium text-gray-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($receivables as $receivable)
                        <tr class="hover:bg-gray-700/30 transition">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-200">{{ $receivable->debtor_name }}</p>
                                <p class="text-sm text-gray-400">{{ $receivable->title }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($receivable->status === 'paid')
                                    <span class="px-2.5 py-1 rounded-lg bg-green-500/20 text-green-400 text-xs font-semibold">Lunas</span>
                                @elseif($receivable->status === 'partial')
                                    <span class="px-2.5 py-1 rounded-lg bg-blue-500/20 text-blue-400 text-xs font-semibold">Dicicil</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg bg-amber-500/20 text-amber-400 text-xs font-semibold">Belum Lunas</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-medium text-gray-300">Rp {{ number_format($receivable->amount ?? $receivable->total_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right font-semibold text-amber-400">Rp {{ number_format($receivable->remaining_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('receivables.edit', $receivable) }}" class="p-2 rounded-lg hover:bg-gray-600 transition">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('receivables.destroy', $receivable) }}" method="POST" onsubmit="return confirm('Hapus data piutang ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg hover:bg-red-500/20 transition">
                                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                Belum ada data piutang
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($receivables->hasPages())
            <div class="px-6 py-4 border-t border-gray-700/50">
                {{ $receivables->links() }}
            </div>
        @endif
    </div>

    <!-- Script Chart.js & Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('receivableChart').getContext('2d');
            
            const labels = {!! json_encode($chartLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']) !!};
            const data = {!! json_encode($chartData ?? [0, 0, 0, 0, 0, 0]) !!};

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Piutang Baru (Rp)',
                        data: data,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#3b82f6'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            grid: { color: 'rgba(255, 255, 255, 0.05)' },
                            ticks: { color: '#9ca3af' }
                        },
                        y: {
                            grid: { color: 'rgba(255, 255, 255, 0.05)' },
                            ticks: { color: '#9ca3af' }
                        }
                    }
                }
            });
        });
    </script>
</x-layouts.dashboard>