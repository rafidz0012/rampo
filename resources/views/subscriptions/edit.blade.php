<x-layouts.dashboard>
    <x-slot name="header">Edit Langganan</x-slot>

    <div class="max-w-2xl">
        <form action="{{ route('subscriptions.update', $subscription) }}" method="POST"
            class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50 space-y-6">
            @csrf @method('PUT')
            <div>
                <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nama Layanan</label>
                <input type="text" name="name" id="name" value="{{ old('name', $subscription->name) }}" required
                    class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-300 mb-2">Biaya (Rp)</label>
                    <input type="number" name="amount" id="amount" value="{{ old('amount', $subscription->amount) }}"
                        required min="0" step="0.01"
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl">
                </div>
                <div>
                    <label for="billing_cycle" class="block text-sm font-medium text-gray-300 mb-2">Siklus</label>
                    <select name="billing_cycle" id="billing_cycle" required
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl">
                        <option value="monthly" {{ $subscription->billing_cycle === 'monthly' ? 'selected' : '' }}>Bulanan
                        </option>
                        <option value="quarterly" {{ $subscription->billing_cycle === 'quarterly' ? 'selected' : '' }}>Per
                            3 Bulan</option>
                        <option value="yearly" {{ $subscription->billing_cycle === 'yearly' ? 'selected' : '' }}>Tahunan
                        </option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="next_billing_date" class="block text-sm font-medium text-gray-300 mb-2">Tagihan
                        Berikutnya</label>
                    <input type="date" name="next_billing_date" id="next_billing_date"
                        value="{{ old('next_billing_date', $subscription->next_billing_date->format('Y-m-d')) }}"
                        required class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                    <select name="status" id="status" required
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl">
                        <option value="active" {{ $subscription->status === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="paused" {{ $subscription->status === 'paused' ? 'selected' : '' }}>Dijeda</option>
                        <option value="cancelled" {{ $subscription->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan
                        </option>
                    </select>
                </div>
            </div>
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-300 mb-2">Catatan</label>
                <textarea name="notes" id="notes" rows="3"
                    class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl resize-none">{{ old('notes', $subscription->notes) }}</textarea>
            </div>
            <div class="flex items-center gap-4 pt-4">
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-purple-500 to-violet-600 rounded-xl font-medium shadow-lg shadow-purple-500/25">Perbarui</button>
                <a href="{{ route('subscriptions.index') }}"
                    class="px-6 py-3 bg-gray-700 hover:bg-gray-600 rounded-xl font-medium transition">Batal</a>
            </div>
        </form>
    </div>
</x-layouts.dashboard>