<x-layouts.dashboard>
    <x-slot name="header">Tambah Pemasukan</x-slot>

    <div class="max-w-2xl">
        <form action="{{ route('incomes.store') }}" method="POST"
            class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50 space-y-6">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium text-gray-300 mb-2">Judul</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500/50 focus:border-green-500 transition"
                    placeholder="Contoh: Gaji Januari">
                @error('title')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-300 mb-2">Jumlah (Rp)</label>
                    <input type="number" name="amount" id="amount" value="{{ old('amount') }}" required min="0"
                        step="0.01"
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500/50 focus:border-green-500 transition"
                        placeholder="0">
                    @error('amount')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-gray-300 mb-2">Kategori</label>
                    <select name="category" id="category" required
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500/50 focus:border-green-500 transition">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="date" class="block text-sm font-medium text-gray-300 mb-2">Tanggal</label>
                <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" required
                    class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500/50 focus:border-green-500 transition">
                @error('date')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-gray-300 mb-2">Catatan (Opsional)</label>
                <textarea name="notes" id="notes" rows="3"
                    class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500/50 focus:border-green-500 transition resize-none"
                    placeholder="Tambahkan catatan...">{{ old('notes') }}</textarea>
                @error('notes')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 rounded-xl font-medium transition-all shadow-lg shadow-green-500/25">
                    Simpan Pemasukan
                </button>
                <a href="{{ route('incomes.index') }}"
                    class="px-6 py-3 bg-gray-700 hover:bg-gray-600 rounded-xl font-medium transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.dashboard>