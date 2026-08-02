<x-layouts.dashboard>
    <x-slot name="header">Tambah Hutang</x-slot>

    <div class="max-w-2xl">
        <form action="{{ route('debt.store') }}" method="POST"
            class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50 space-y-6">
            @csrf

            <!-- Judul Hutang -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-300 mb-2">Judul</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition"
                    placeholder="Contoh: Pinjaman Bank, Hutang Server, dll.">
                @error('title')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>

            <!-- Nama Pemberi Utang & Total -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="creditor_name" class="block text-sm font-medium text-gray-300 mb-2">Pemberi Hutang</label>
                    <input type="text" name="creditor_name" id="creditor_name" value="{{ old('creditor_name') }}" required
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition"
                        placeholder="Contoh: Bank BCA, Budi, dll.">
                    @error('creditor_name')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="total" class="block text-sm font-medium text-gray-300 mb-2">Total Hutang (Rp)</label>
                    <input type="number" name="total" id="total" value="{{ old('total') }}" required min="0"
                        step="0.01"
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition"
                        placeholder="0">
                    @error('total')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Tanggal Jatuh Tempo -->
            <div>
                <label for="due_date" class="block text-sm font-medium text-gray-300 mb-2">Jatuh Tempo (Opsional)</label>
                <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}"
                    class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition">
                @error('due_date')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>

            <!-- Catatan -->
            <div>
                <label for="note" class="block text-sm font-medium text-gray-300 mb-2">Catatan (Opsional)</label>
                <textarea name="note" id="note" rows="3"
                    class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition resize-none"
                    placeholder="Tambahkan catatan atau keterangan tambahan...">{{ old('note') }}</textarea>
                @error('note')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-4 pt-4">
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 rounded-xl font-medium transition-all shadow-lg shadow-red-500/25">
                    Simpan Hutang
                </button>
                <a href="{{ route('debt.index') }}"
                    class="px-6 py-3 bg-gray-700 hover:bg-gray-600 rounded-xl font-medium transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.dashboard>