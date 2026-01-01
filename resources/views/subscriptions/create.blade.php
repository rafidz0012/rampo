<x-layouts.dashboard>
    <x-slot name="header">Tambah Langganan</x-slot>

    <div class="max-w-2xl">
        <form action="{{ route('subscriptions.store') }}" method="POST"
            class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50 space-y-6">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nama Layanan</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500/50 transition"
                    placeholder="Contoh: Netflix, Spotify">
                @error('name')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-300 mb-2">Biaya (Rp)</label>
                    <input type="number" name="amount" id="amount" value="{{ old('amount') }}" required min="0"
                        step="0.01" class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl">
                </div>
                <div>
                    <label for="billing_cycle" class="block text-sm font-medium text-gray-300 mb-2">Siklus
                        Tagihan</label>
                    <select name="billing_cycle" id="billing_cycle" required
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl">
                        <option value="monthly">Bulanan</option>
                        <option value="quarterly">Per 3 Bulan</option>
                        <option value="yearly">Tahunan</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="next_billing_date" class="block text-sm font-medium text-gray-300 mb-2">Tanggal Tagihan
                        Berikutnya</label>
                    <input type="date" name="next_billing_date" id="next_billing_date"
                        value="{{ old('next_billing_date', date('Y-m-d')) }}" required
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                    <select name="status" id="status" required
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl">
                        <option value="active">Aktif</option>
                        <option value="paused">Dijeda</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                </div>
            </div>
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-300 mb-2">Catatan</label>
                <textarea name="notes" id="notes" rows="3"
                    class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl resize-none">{{ old('notes') }}</textarea>
            </div>
            <div class="flex items-center gap-4 pt-4">
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-purple-500 to-violet-600 rounded-xl font-medium shadow-lg shadow-purple-500/25">Simpan</button>
                <a href="{{ route('subscriptions.index') }}"
                    class="px-6 py-3 bg-gray-700 hover:bg-gray-600 rounded-xl font-medium transition">Batal</a>
            </div>
        </form>
    </div>
</x-layouts.dashboard>