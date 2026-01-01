<x-layouts.dashboard>
    <x-slot name="header">Edit To-do</x-slot>

    <div class="max-w-2xl">
        <form action="{{ route('todos.update', $todo) }}" method="POST"
            class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50 space-y-6">
            @csrf @method('PUT')
            <div>
                <label for="title" class="block text-sm font-medium text-gray-300 mb-2">Judul</label>
                <input type="text" name="title" id="title" value="{{ old('title', $todo->title) }}" required
                    class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Deskripsi</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl resize-none">{{ old('description', $todo->description) }}</textarea>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-300 mb-2">Deadline</label>
                    <input type="datetime-local" name="due_date" id="due_date"
                        value="{{ old('due_date', $todo->due_date?->format('Y-m-d\TH:i')) }}"
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl">
                </div>
                <div>
                    <label for="reminder_at" class="block text-sm font-medium text-gray-300 mb-2">Reminder</label>
                    <input type="datetime-local" name="reminder_at" id="reminder_at"
                        value="{{ old('reminder_at', $todo->reminder_at?->format('Y-m-d\TH:i')) }}"
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Prioritas</label>
                <div class="flex gap-4">
                    @foreach(['low' => 'Rendah', 'medium' => 'Sedang', 'high' => 'Tinggi'] as $val => $label)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="priority" value="{{ $val }}" {{ old('priority', $todo->priority) === $val ? 'checked' : '' }}
                                class="w-4 h-4 bg-gray-700 border-gray-600 text-cyan-500 focus:ring-cyan-500">
                            <span class="text-sm">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="flex items-center gap-4 pt-4">
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-cyan-500 to-teal-600 rounded-xl font-medium shadow-lg shadow-cyan-500/25">Perbarui</button>
                <a href="{{ route('todos.index') }}"
                    class="px-6 py-3 bg-gray-700 hover:bg-gray-600 rounded-xl font-medium transition">Batal</a>
            </div>
        </form>
    </div>
</x-layouts.dashboard>