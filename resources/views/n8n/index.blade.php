<x-layouts.dashboard>
    <x-slot name="header">N8n Clipper</x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-700/50">
            @if (session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 flex items-center gap-3">
                     <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('n8n.send') }}" class="space-y-6">
                @csrf

                <!-- Title -->
                <div>
                    <x-input-label for="title" :value="__('Title')" class="text-gray-300" />
                    <x-text-input id="title" class="block mt-1 w-full bg-gray-700/50 border-gray-600 text-gray-100 focus:ring-blue-500 focus:border-blue-500" type="text" name="title" :value="old('title')" required autofocus />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <!-- URL -->
                <div>
                    <x-input-label for="url" :value="__('URL')" class="text-gray-300" />
                    <x-text-input id="url" class="block mt-1 w-full bg-gray-700/50 border-gray-600 text-gray-100 focus:ring-blue-500 focus:border-blue-500" type="url" name="url" :value="old('url')" required />
                    <x-input-error :messages="$errors->get('url')" class="mt-2" />
                </div>

                <!-- Content -->
                <div>
                    <x-input-label for="content" :value="__('Content')" class="text-gray-300" />
                    <textarea id="content" name="content" rows="6" class="block mt-1 w-full rounded-xl bg-gray-700/50 border-gray-600 text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('content') }}</textarea>
                    <x-input-error :messages="$errors->get('content')" class="mt-2" />
                </div>

                <!-- Webhook URL (Optional Override) -->
                <div>
                     <x-input-label for="webhook_url" :value="__('Webhook URL (Optional Override)')" class="text-gray-300" />
                     <x-text-input id="webhook_url" class="block mt-1 w-full bg-gray-700/50 border-gray-600 text-gray-100 focus:ring-blue-500 focus:border-blue-500" type="url" name="webhook_url" :value="old('webhook_url')" placeholder="Leave empty to use default env setting" />
                     <x-input-error :messages="$errors->get('webhook_url')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end pt-4">
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-900 transition-all duration-200">
                        {{ __('Send to n8n') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
