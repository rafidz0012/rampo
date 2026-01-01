<x-layouts.dashboard>
    <x-slot name="header">Upload Dokumen</x-slot>

    <div class="max-w-2xl">
        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50 space-y-6">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nama Dokumen</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl focus:ring-2 focus:ring-amber-500/50 transition">
                @error('name')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="file" class="block text-sm font-medium text-gray-300 mb-2">File</label>
                <div class="flex items-center justify-center w-full">
                    <label for="file"
                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-600 border-dashed rounded-xl cursor-pointer hover:bg-gray-700/50 transition">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            <p class="text-sm text-gray-400">Klik untuk upload atau drag & drop</p>
                            <p class="text-xs text-gray-500">Maksimal 10MB</p>
                        </div>
                        <input id="file" name="file" type="file" class="hidden" required />
                    </label>
                </div>
                @error('file')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="category" class="block text-sm font-medium text-gray-300 mb-2">Kategori</label>
                <select name="category" id="category" required
                    class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl">
                    @foreach($categories as $cat)<option value="{{ $cat }}">{{ ucfirst($cat) }}</option>@endforeach
                </select>
            </div>
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-300 mb-2">Catatan (Opsional)</label>
                <textarea name="notes" id="notes" rows="3"
                    class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl resize-none">{{ old('notes') }}</textarea>
            </div>
            <div class="flex items-center gap-4 pt-4">
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl font-medium shadow-lg shadow-amber-500/25">Upload</button>
                <a href="{{ route('documents.index') }}"
                    class="px-6 py-3 bg-gray-700 hover:bg-gray-600 rounded-xl font-medium transition">Batal</a>
            </div>
        </form>
    </div>
</x-layouts.dashboard>