<x-layouts.dashboard>
    <x-slot name="header">Edit Piutang</x-slot>

    <div class="max-w-2xl">
        <form action="{{ route('receivables.update', $receivable) }}" method="POST"
            class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50 space-y-6">
            @csrf @method('PUT')

            <div>
                <label for="title" class="block text-sm font-medium text-gray-300 mb-2">Judul Piutang</label>
                <input type="text" name="title" id="title" value="{{ old('title', $receivable->title) }}" required
                    class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl @error('title') border-red-500 @enderror">
                @error('title')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="debtor_name" class="block text-sm font-medium text-gray-300 mb-2">Nama Peminjam</label>
                <input type="text" name="debtor_name" id="debtor_name"
                    value="{{ old('debtor_name', $receivable->debtor_name) }}" required
                    class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl @error('debtor_name') border-red-500 @enderror">
                @error('debtor_name')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="total" class="block text-sm font-medium text-gray-300 mb-2">Total Piutang (Rp)</label>
                    <input type="number" name="total" id="total" value="{{ old('total', $receivable->total) }}"
                        required min="0" step="0.01"
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl @error('total') border-red-500 @enderror">
                    @error('total')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="remaining_amount" class="block text-sm font-medium text-gray-300 mb-2">Sisa Piutang
                        (Rp)</label>
                    <input type="number" name="remaining_amount" id="remaining_amount"
                        value="{{ old('remaining_amount', $receivable->remaining_amount) }}" required min="0"
                        step="0.01"
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl @error('remaining_amount') border-red-500 @enderror">
                    @error('remaining_amount')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Isi 0 jika sudah lunas — status otomatis berubah menjadi
                        "Lunas".</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-300 mb-2">Jatuh Tempo</label>
                    <input type="date" name="due_date" id="due_date"
                        value="{{ old('due_date', optional($receivable->due_date)->format('Y-m-d')) }}"
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl @error('due_date') border-red-500 @enderror">
                    @error('due_date')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                    <select name="status" id="status" required
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl @error('status') border-red-500 @enderror">
                        <option value="pending" {{ $receivable->status === 'pending' ? 'selected' : '' }}>Belum
                            Dibayar</option>
                        <option value="partial" {{ $receivable->status === 'partial' ? 'selected' : '' }}>Dibayar
                            Sebagian</option>
                        <option value="paid" {{ $receivable->status === 'paid' ? 'selected' : '' }}>Lunas</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="note" class="block text-sm font-medium text-gray-300 mb-2">Catatan</label>
                <textarea name="note" id="note" rows="3"
                    class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl resize-none @error('note') border-red-500 @enderror">{{ old('note', $receivable->note) }}</textarea>
                @error('note')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-purple-500 to-violet-600 rounded-xl font-medium shadow-lg shadow-purple-500/25">Perbarui</button>
                <a href="{{ route('receivables.index') }}"
                    class="px-6 py-3 bg-gray-700 hover:bg-gray-600 rounded-xl font-medium transition">Batal</a>
            </div>
        </form>
    </div>
</x-layouts.dashboard>