<x-layouts.dashboard>
    <x-slot name="header">Dashboard</x-slot>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Monthly Income -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <span class="text-xs text-gray-500">Bulan Ini</span>
            </div>
            <p class="text-2xl font-bold text-green-400">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</p>
            <p class="text-sm text-gray-400 mt-1">Pemasukan</p>
        </div>

        <!-- Monthly Expense -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-red-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                    </svg>
                </div>
                <span class="text-xs text-gray-500">Bulan Ini</span>
            </div>
            <p class="text-2xl font-bold text-red-400">Rp {{ number_format($monthlyExpense, 0, ',', '.') }}</p>
            <p class="text-sm text-gray-400 mt-1">Pengeluaran</p>
        </div>

        <!-- Balance -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs text-gray-500">Bulan Ini</span>
            </div>
            <p class="text-2xl font-bold {{ $balance >= 0 ? 'text-blue-400' : 'text-orange-400' }}">Rp
                {{ number_format($balance, 0, ',', '.') }}</p>
            <p class="text-sm text-gray-400 mt-1">Saldo</p>
        </div>

        <!-- Subscriptions -->
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </div>
                <span class="text-xs text-gray-500">Per Bulan</span>
            </div>
            <p class="text-2xl font-bold text-purple-400">Rp {{ number_format($monthlySubscriptions, 0, ',', '.') }}</p>
            <p class="text-sm text-gray-400 mt-1">Langganan Aktif</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Upcoming Bills -->
            @if($upcomingBills->count() > 0)
                <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50">
                    <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Tagihan Mendatang (7 Hari)
                    </h2>
                    <div class="space-y-3">
                        @foreach($upcomingBills as $bill)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-gray-700/30">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-gradient-to-br from-yellow-500/20 to-orange-500/20 flex items-center justify-center">
                                        <span class="text-sm font-bold text-yellow-400">{{ substr($bill->name, 0, 2) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $bill->name }}</p>
                                        <p class="text-sm text-gray-400">{{ $bill->next_billing_date->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <p class="font-semibold text-yellow-400">Rp {{ number_format($bill->amount, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Recent Transactions -->
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50">
                <h2 class="text-lg font-semibold mb-4">Transaksi Terbaru</h2>
                <div class="space-y-3">
                    @forelse($recentIncomes->merge($recentExpenses)->sortByDesc('created_at')->take(8) as $transaction)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-gray-700/30">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-lg {{ $transaction instanceof \App\Models\Income ? 'bg-green-500/20' : 'bg-red-500/20' }} flex items-center justify-center">
                                    @if($transaction instanceof \App\Models\Income)
                                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 12H4" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium">{{ $transaction->title }}</p>
                                    <p class="text-sm text-gray-400">{{ $transaction->date->format('d M Y') }} •
                                        {{ ucfirst($transaction->category) }}</p>
                                </div>
                            </div>
                            <p
                                class="font-semibold {{ $transaction instanceof \App\Models\Income ? 'text-green-400' : 'text-red-400' }}">
                                {{ $transaction instanceof \App\Models\Income ? '+' : '-' }} Rp
                                {{ number_format($transaction->amount, 0, ',', '.') }}
                            </p>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-8">Belum ada transaksi</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Pending Todos -->
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">To-do</h2>
                    @if($overdueTodos > 0)
                        <span
                            class="px-2 py-1 rounded-full bg-red-500/20 text-red-400 text-xs font-medium">{{ $overdueTodos }}
                            overdue</span>
                    @endif
                </div>
                <div class="space-y-3">
                    @forelse($pendingTodos as $todo)
                        <div
                            class="flex items-start gap-3 p-3 rounded-xl bg-gray-700/30 {{ $todo->isOverdue() ? 'border border-red-500/30' : '' }}">
                            <form action="{{ route('todos.toggle', $todo) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="w-5 h-5 rounded-full border-2 border-gray-500 hover:border-blue-400 flex items-center justify-center transition mt-0.5">
                                </button>
                            </form>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium truncate">{{ $todo->title }}</p>
                                @if($todo->due_date)
                                    <p class="text-sm {{ $todo->isOverdue() ? 'text-red-400' : 'text-gray-400' }}">
                                        {{ $todo->due_date->format('d M Y') }}
                                    </p>
                                @endif
                            </div>
                            <span class="px-2 py-0.5 rounded text-xs font-medium
                                {{ $todo->priority === 'high' ? 'bg-red-500/20 text-red-400' : '' }}
                                {{ $todo->priority === 'medium' ? 'bg-yellow-500/20 text-yellow-400' : '' }}
                                {{ $todo->priority === 'low' ? 'bg-green-500/20 text-green-400' : '' }}
                            ">{{ ucfirst($todo->priority) }}</span>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-4">Tidak ada tugas pending</p>
                    @endforelse
                </div>
                <a href="{{ route('todos.index') }}"
                    class="block text-center text-sm text-blue-400 hover:text-blue-300 mt-4 transition">Lihat semua
                    →</a>
            </div>

            <!-- Recent Notes -->
            <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50">
                <h2 class="text-lg font-semibold mb-4">Catatan Terbaru</h2>
                <div class="space-y-3">
                    @forelse($recentNotes as $note)
                        <a href="{{ route('notes.show', $note) }}"
                            class="block p-3 rounded-xl bg-gray-700/30 hover:bg-gray-700/50 transition">
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-3 h-3 rounded-full" style="background-color: {{ $note->color }}"></div>
                                <p class="font-medium truncate">{{ $note->title }}</p>
                                @if($note->is_pinned)
                                    <svg class="w-4 h-4 text-yellow-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M5 5a2 2 0 012-2h6a2 2 0 012 2v2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2v4a2 2 0 01-2 2H7a2 2 0 01-2-2v-4H3a2 2 0 01-2-2V9a2 2 0 012-2h2V5z" />
                                    </svg>
                                @endif
                            </div>
                            <p class="text-sm text-gray-400 line-clamp-2">{{ Str::limit(strip_tags($note->content), 80) }}
                            </p>
                        </a>
                    @empty
                        <p class="text-center text-gray-500 py-4">Belum ada catatan</p>
                    @endforelse
                </div>
                <a href="{{ route('notes.index') }}"
                    class="block text-center text-sm text-blue-400 hover:text-blue-300 mt-4 transition">Lihat semua
                    →</a>
            </div>
        </div>
    </div>
</x-layouts.dashboard>