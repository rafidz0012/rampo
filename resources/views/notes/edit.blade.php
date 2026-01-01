<x-layouts.dashboard>
    <x-slot name="header">Edit Catatan</x-slot>

    <div class="max-w-3xl">
        <form action="{{ route('notes.update', $note) }}" method="POST" class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50 space-y-6">
            @csrf @method('PUT')
            <div>
                <label for="title" class="block text-sm font-medium text-gray-300 mb-2">Judul</label>
                <input type="text" name="title" id="title" value="{{ old('title', $note->title) }}" required
                    class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-lg font-medium">
            </div>
            <div>
                <label for="content" class="block text-sm font-medium text-gray-300 mb-2">Isi Catatan</label>
                <textarea name="content" id="content" rows="10" class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl resize-none">{{ old('content', $note->content) }}</textarea>
            </div>
            <div class="flex flex-wrap items-center gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Warna</label>
                    <div class="flex items-center gap-2">
                        @php $colors = ['#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899']; @endphp
                        @foreach($colors as $color)
                        <label class="cursor-pointer">
                            <input type="radio" name="color" value="{{ $color }}" class="sr-only peer" {{ old('color', $note->color) === $color ? 'checked' : '' }}>
                            <div class="w-8 h-8 rounded-lg peer-checked:ring-2 peer-checked:ring-white transition" style="background-color: {{ $color }}"></div>
                        </label>
                        @endforeach
                    </div>
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_pinned" value="1" {{ old('is_pinned', $note->is_pinned) ? 'checked' : '' }}
                        class="w-5 h-5 rounded bg-gray-700 border-gray-600 text-blue-500">
                    <span class="text-sm text-gray-300">Pin catatan</span>
                </label>
            </div>
            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl font-medium shadow-lg shadow-blue-500/25">Perbarui</button>
                <a href="{{ route('notes.show', $note) }}" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 rounded-xl font-medium transition">Batal</a>
            </div>
        </form>
    </div>
</x-layouts.dashboard>
