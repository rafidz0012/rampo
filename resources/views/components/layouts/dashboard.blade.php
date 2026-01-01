<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} - Rampo</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-900 text-gray-100">
    <div class="min-h-screen flex" x-data="{ sidebarOpen: true }">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'"
            class="fixed inset-y-0 left-0 z-50 bg-gray-800/50 backdrop-blur-xl border-r border-gray-700/50 transition-all duration-300 flex flex-col">
            <!-- Logo -->
            <div class="h-16 flex items-center justify-between px-4 border-b border-gray-700/50">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span x-show="sidebarOpen" x-cloak
                        class="font-bold text-xl bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">Rampo</span>
                </a>
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-gray-700/50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <!-- Dashboard -->
                <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="home">
                    Dashboard
                </x-sidebar-link>

                <!-- Keuangan Section -->
                <div class="pt-4">
                    <p x-show="sidebarOpen" x-cloak
                        class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Keuangan</p>
                    <x-sidebar-link :href="route('incomes.index')" :active="request()->routeIs('incomes.*')"
                        icon="trending-up">
                        Pemasukan
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('expenses.index')" :active="request()->routeIs('expenses.*')"
                        icon="trending-down">
                        Pengeluaran
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('subscriptions.index')" :active="request()->routeIs('subscriptions.*')"
                        icon="refresh">
                        Langganan
                    </x-sidebar-link>
                </div>

                <!-- Productivity Section -->
                <div class="pt-4">
                    <p x-show="sidebarOpen" x-cloak
                        class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Produktivitas</p>
                    <x-sidebar-link :href="route('notes.index')" :active="request()->routeIs('notes.*')"
                        icon="document-text">
                        Catatan
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('todos.index')" :active="request()->routeIs('todos.*')"
                        icon="check-circle">
                        To-do
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('documents.index')" :active="request()->routeIs('documents.*')"
                        icon="folder">
                        Dokumen
                    </x-sidebar-link>
                </div>
            </nav>

            <!-- User Section -->
            <div class="p-3 border-t border-gray-700/50">
                <x-sidebar-link :href="route('settings.index')" :active="request()->routeIs('settings.*')" icon="cog">
                    Settings
                </x-sidebar-link>
                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-red-500/10 transition-all duration-200">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span x-show="sidebarOpen" x-cloak class="text-sm font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main :class="sidebarOpen ? 'ml-64' : 'ml-20'" class="flex-1 transition-all duration-300">
            <!-- Top Bar -->
            <header
                class="h-16 bg-gray-800/30 backdrop-blur-md border-b border-gray-700/50 flex items-center justify-between px-6 sticky top-0 z-40">
                <div class="flex items-center gap-4">
                    @isset($header)
                        <h1 class="text-xl font-semibold">{{ $header }}</h1>
                    @endisset
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-400">{{ now()->format('l, d F Y') }}</span>
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <span class="text-sm font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div
                        class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div
                        class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>
</body>

</html>