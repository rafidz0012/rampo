<x-layouts.dashboard>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-cyan-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                </svg>
            </div>
            <span>Server Monitoring</span>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Status Bar -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div id="status-indicator" class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>
                <span class="text-sm text-gray-400">Server Status: <span id="status-text" class="text-emerald-400 font-medium">Online</span></span>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Last updated: <span id="last-update" class="text-gray-400">-</span></span>
            </div>
        </div>

        <!-- Main Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- CPU Card -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-orange-500/10 to-red-500/10 border border-orange-500/20 p-6 group hover:border-orange-500/40 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-orange-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center shadow-lg shadow-orange-500/25">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                            </svg>
                        </div>
                        <div id="cpu-trend" class="flex items-center gap-1 text-xs px-2 py-1 rounded-full bg-orange-500/20 text-orange-400">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                            <span>-</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 mb-1">CPU Load</p>
                    <div class="flex items-end gap-2">
                        <span id="cpu-value" class="text-3xl font-bold text-white">-</span>
                        <span class="text-gray-500 mb-1">%</span>
                    </div>
                    <div class="mt-4 h-2 bg-gray-800 rounded-full overflow-hidden">
                        <div id="cpu-bar" class="h-full bg-gradient-to-r from-orange-500 to-red-500 rounded-full transition-all duration-500 ease-out" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- RAM Card -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500/10 to-cyan-500/10 border border-blue-500/20 p-6 group hover:border-blue-500/40 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center shadow-lg shadow-blue-500/25">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <div id="ram-trend" class="flex items-center gap-1 text-xs px-2 py-1 rounded-full bg-blue-500/20 text-blue-400">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                            <span>-</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 mb-1">Memory Usage</p>
                    <div class="flex items-end gap-2">
                        <span id="ram-used" class="text-3xl font-bold text-white">-</span>
                        <span id="ram-total" class="text-gray-500 mb-1">/ - MB</span>
                    </div>
                    <div class="mt-4 h-2 bg-gray-800 rounded-full overflow-hidden">
                        <div id="ram-bar" class="h-full bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full transition-all duration-500 ease-out" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- Disk Card -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-500/10 to-pink-500/10 border border-purple-500/20 p-6 group hover:border-purple-500/40 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shadow-lg shadow-purple-500/25">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                            </svg>
                        </div>
                        <div id="disk-status" class="flex items-center gap-1 text-xs px-2 py-1 rounded-full bg-emerald-500/20 text-emerald-400">
                            <span>Healthy</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 mb-1">Disk Usage</p>
                    <div class="flex items-end gap-2">
                        <span id="disk-value" class="text-3xl font-bold text-white">-</span>
                        <span class="text-gray-500 mb-1">%</span>
                    </div>
                    <div class="mt-4 h-2 bg-gray-800 rounded-full overflow-hidden">
                        <div id="disk-bar" class="h-full bg-gradient-to-r from-purple-500 to-pink-500 rounded-full transition-all duration-500 ease-out" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- Uptime Card -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500/10 to-teal-500/10 border border-emerald-500/20 p-6 group hover:border-emerald-500/40 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/25">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex items-center gap-1 text-xs px-2 py-1 rounded-full bg-emerald-500/20 text-emerald-400">
                            <span>Running</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 mb-1">Uptime</p>
                    <div class="flex items-end gap-2">
                        <span id="uptime-value" class="text-xl font-bold text-white">-</span>
                    </div>
                    <div class="mt-4 flex items-center gap-2 text-xs text-gray-500">
                        <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/>
                        </svg>
                        <span id="server-ip">Loading IP...</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- CPU History Chart -->
            <div class="rounded-2xl bg-gray-800/30 backdrop-blur-sm border border-gray-700/50 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        CPU History
                    </h3>
                    <span class="text-xs text-gray-500">Last 60 seconds</span>
                </div>
                <div class="h-32 flex items-end gap-1" id="cpu-chart">
                    <!-- Bars will be generated by JavaScript -->
                </div>
            </div>

            <!-- Memory Distribution -->
            <div class="rounded-2xl bg-gray-800/30 backdrop-blur-sm border border-gray-700/50 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                        </svg>
                        Memory Distribution
                    </h3>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                            <span class="text-sm text-gray-400">Used Memory</span>
                        </div>
                        <span id="mem-used-detail" class="text-sm font-medium text-white">- MB</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full bg-gray-600"></div>
                            <span class="text-sm text-gray-400">Free Memory</span>
                        </div>
                        <span id="mem-free-detail" class="text-sm font-medium text-white">- MB</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                            <span class="text-sm text-gray-400">Buffers/Cache</span>
                        </div>
                        <span id="mem-buffer-detail" class="text-sm font-medium text-white">- MB</span>
                    </div>
                    <div class="mt-4 h-3 bg-gray-800 rounded-full overflow-hidden flex">
                        <div id="mem-used-bar" class="h-full bg-blue-500 transition-all duration-500" style="width: 0%"></div>
                        <div id="mem-buffer-bar" class="h-full bg-emerald-500 transition-all duration-500" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div class="rounded-2xl bg-gray-800/30 backdrop-blur-sm border border-gray-700/50 p-6">
            <h3 class="text-lg font-semibold text-white mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                System Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-4 rounded-xl bg-gray-800/50 border border-gray-700/30">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Hostname</p>
                    <p id="hostname" class="text-sm font-medium text-white">-</p>
                </div>
                <div class="p-4 rounded-xl bg-gray-800/50 border border-gray-700/30">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">OS</p>
                    <p id="os-info" class="text-sm font-medium text-white">-</p>
                </div>
                <div class="p-4 rounded-xl bg-gray-800/50 border border-gray-700/30">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Kernel</p>
                    <p id="kernel" class="text-sm font-medium text-white">-</p>
                </div>
                <div class="p-4 rounded-xl bg-gray-800/50 border border-gray-700/30">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">CPU Cores</p>
                    <p id="cpu-cores" class="text-sm font-medium text-white">-</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // CPU History Data
        const cpuHistory = [];
        const maxHistory = 30;
        let previousCpuLoad = 0;
        let previousRamUsed = 0;

        // Initialize CPU Chart
        function initCpuChart() {
            const chart = document.getElementById('cpu-chart');
            for (let i = 0; i < maxHistory; i++) {
                const bar = document.createElement('div');
                bar.className = 'flex-1 bg-gradient-to-t from-orange-500/20 to-orange-500/60 rounded-t transition-all duration-300';
                bar.style.height = '0%';
                chart.appendChild(bar);
            }
        }

        function updateCpuChart(value) {
            cpuHistory.push(value);
            if (cpuHistory.length > maxHistory) {
                cpuHistory.shift();
            }

            const bars = document.querySelectorAll('#cpu-chart > div');
            bars.forEach((bar, index) => {
                const val = cpuHistory[index] || 0;
                bar.style.height = `${Math.min(val, 100)}%`;
                
                // Color based on load
                if (val > 80) {
                    bar.className = 'flex-1 bg-gradient-to-t from-red-500/40 to-red-500 rounded-t transition-all duration-300';
                } else if (val > 50) {
                    bar.className = 'flex-1 bg-gradient-to-t from-yellow-500/40 to-yellow-500 rounded-t transition-all duration-300';
                } else {
                    bar.className = 'flex-1 bg-gradient-to-t from-orange-500/20 to-orange-500/60 rounded-t transition-all duration-300';
                }
            });
        }

        function updateTrend(elementId, current, previous) {
            const element = document.getElementById(elementId);
            if (!element) return;
            
            const diff = current - previous;
            const arrow = diff > 0 ? 
                '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>' :
                '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>';
            
            element.innerHTML = arrow + `<span>${Math.abs(diff).toFixed(1)}%</span>`;
        }

        async function loadStats() {
            try {
                const res = await fetch('/monitor/stats');
                const data = await res.json();

                // Update CPU
                const cpuLoad = parseFloat(data.cpu_load) || 0;
                document.getElementById('cpu-value').innerText = cpuLoad.toFixed(1);
                document.getElementById('cpu-bar').style.width = `${Math.min(cpuLoad * 10, 100)}%`;
                updateCpuChart(cpuLoad * 10);
                updateTrend('cpu-trend', cpuLoad * 10, previousCpuLoad);
                previousCpuLoad = cpuLoad * 10;

                // Update RAM
                const ramParts = data.ram.split('/');
                const ramUsed = parseInt(ramParts[0]) || 0;
                const ramTotal = parseInt(ramParts[1]) || 1;
                const ramPercent = (ramUsed / ramTotal) * 100;
                
                document.getElementById('ram-used').innerText = ramUsed;
                document.getElementById('ram-total').innerText = `/ ${ramTotal} MB`;
                document.getElementById('ram-bar').style.width = `${ramPercent}%`;
                updateTrend('ram-trend', ramPercent, previousRamUsed);
                previousRamUsed = ramPercent;

                // Update Memory Details
                const ramFree = ramTotal - ramUsed;
                document.getElementById('mem-used-detail').innerText = `${ramUsed} MB`;
                document.getElementById('mem-free-detail').innerText = `${ramFree} MB`;
                document.getElementById('mem-buffer-detail').innerText = `${data.buffers || 0} MB`;
                document.getElementById('mem-used-bar').style.width = `${ramPercent}%`;

                // Update Disk
                const diskUsed = parseFloat(data.disk) || 0;
                document.getElementById('disk-value').innerText = diskUsed.toFixed(1);
                document.getElementById('disk-bar').style.width = `${diskUsed}%`;
                
                // Disk status indicator
                const diskStatus = document.getElementById('disk-status');
                if (diskUsed > 90) {
                    diskStatus.className = 'flex items-center gap-1 text-xs px-2 py-1 rounded-full bg-red-500/20 text-red-400';
                    diskStatus.innerHTML = '<span>Critical</span>';
                } else if (diskUsed > 75) {
                    diskStatus.className = 'flex items-center gap-1 text-xs px-2 py-1 rounded-full bg-yellow-500/20 text-yellow-400';
                    diskStatus.innerHTML = '<span>Warning</span>';
                } else {
                    diskStatus.className = 'flex items-center gap-1 text-xs px-2 py-1 rounded-full bg-emerald-500/20 text-emerald-400';
                    diskStatus.innerHTML = '<span>Healthy</span>';
                }

                // Update Uptime
                document.getElementById('uptime-value').innerText = data.uptime || '-';

                // Update System Info
                if (data.hostname) document.getElementById('hostname').innerText = data.hostname;
                if (data.os) document.getElementById('os-info').innerText = data.os;
                if (data.kernel) document.getElementById('kernel').innerText = data.kernel;
                if (data.cpu_cores) document.getElementById('cpu-cores').innerText = data.cpu_cores + ' Cores';
                if (data.ip) document.getElementById('server-ip').innerText = data.ip;

                // Update timestamp
                const now = new Date();
                document.getElementById('last-update').innerText = now.toLocaleTimeString('id-ID');

            } catch (error) {
                console.error('Failed to fetch stats:', error);
                document.getElementById('status-text').innerText = 'Error';
                document.getElementById('status-indicator').className = 'w-3 h-3 rounded-full bg-red-500 animate-pulse';
            }
        }

        // Initialize
        initCpuChart();
        loadStats();
        setInterval(loadStats, 3000);
    </script>
</x-layouts.dashboard>