<x-layouts.dashboard>
    <x-slot name="header">Settings</x-slot>

    <div class="max-w-2xl">
        <form action="{{ route('settings.update') }}" method="POST"
            class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50 space-y-6">
            @csrf
            @method('PUT')

            <div class="flex items-center gap-4 pb-6 border-b border-gray-700/50">
                <div
                    class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-2xl font-bold">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-xl font-semibold">{{ $user->name }}</h2>
                    <p class="text-gray-400">{{ $user->email }}</p>
                </div>
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nama</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500/50 transition">
                @error('name')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                    class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500/50 transition">
                @error('email')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>

            <div class="pt-4 border-t border-gray-700/50">
                <h3 class="text-lg font-medium mb-4">Ubah</h3>
                <p class="text-sm text-gray-400 mb-4">Kosongkan jika tidak ingin mengubah password</p>

                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-300 mb-2">Password Saat
                            Ini</label>
                        <input type="password" name="current_password" id="current_password"
                            class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500/50 transition">
                        @error('current_password')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password Baru</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500/50 transition">
                        @error('password')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password_confirmation"
                            class="block text-sm font-medium text-gray-300 mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500/50 transition">
                    </div>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl font-medium shadow-lg shadow-blue-500/25 transition-all hover:shadow-xl">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-layouts.dashboard>