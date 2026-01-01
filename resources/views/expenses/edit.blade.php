<x-layouts.dashboard>
    <x-slot name="header">Edit Pengeluaran</x-slot>

    <div class="max-w-2xl">
        <form action="{{ route('expenses.update', $expense) }}" method="POST"
            class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50 space-y-6">
            @csrf @method('PUT')
            <div>
                <label for="title" class="block text-sm font-medium text-gray-300 mb-2">Judul</label>
                <input type="text" name="title" id="title" value="{{ old('title', $expense->title) }}" required
                    class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-300 mb-2">Jumlah (Rp)</label>
                    <input type="number" name="amount" id="amount" value="{{ old('amount', $expense->amount) }}"
                        required min="0" step="0.01"
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl">
                </div>
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-300 mb-2">Kategori</label>
                    <select name="category" id="category" required
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl">
                        @foreach($categories as $cat)<option value="{{ $cat }}" {{ old('category', $expense->category) === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>@endforeach
                    </select>
                </div>
            </div>
            <div>
                <label for="date" class="block text-sm font-medium text-gray-300 mb-2">Tanggal</label>
                <input type="date" name="date" id="date" value="{{ old('date', $expense->date->format('Y-m-d')) }}"
                    required class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl">
            </div>
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-300 mb-2">Catatan</label>
                <textarea name="notes" id="notes" rows="3"
                    class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl resize-none">{{ old('notes', $expense->notes) }}</textarea>
            </div>
            <div class="flex items-center gap-4 pt-4">
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-red-500 to-rose-600 rounded-xl font-medium transition-all shadow-lg shadow-red-500/25">Perbarui</button>
                <a href="{{ route('expenses.index') }}"
                    class="px-6 py-3 bg-gray-700 hover:bg-gray-600 rounded-xl font-medium transition">Batal</a>
            </div>
        </form>
    </div>
</x-layouts.dashboard>