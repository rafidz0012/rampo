<?php

namespace App\Http\Controllers\Server;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServerMonitorController extends Controller
{
    /**
     * Data konfigurasi server yang bisa diubah
     * Anda bisa mengubah nilai-nilai ini sesuai kebutuhan
     */
    protected $config = [
        'server_name' => 'Rampo Server',
        'refresh_interval' => 3000, // milliseconds
    ];

    public function index()
    {
        return view('monitoring.server', [
            'config' => $this->config
        ]);
    }

    public function stats()
    {
        // CPU Load (1 menit, 5 menit, 15 menit)
        $loadAvg = sys_getloadavg();
        $cpuLoad = $loadAvg[0];

        // RAM Details
        $ram = $this->getMemoryInfo();

        // Disk
        $diskInfo = $this->getDiskInfo();

        // Uptime
        $uptime = trim(shell_exec("uptime -p") ?? 'N/A');

        // System Info
        $systemInfo = $this->getSystemInfo();

        return response()->json([
            'cpu_load'    => round($cpuLoad, 2),
            'cpu_load_5'  => round($loadAvg[1] ?? 0, 2),
            'cpu_load_15' => round($loadAvg[2] ?? 0, 2),
            'ram'         => $ram['used'] . '/' . $ram['total'] . ' MB',
            'ram_used'    => $ram['used'],
            'ram_total'   => $ram['total'],
            'ram_free'    => $ram['free'],
            'buffers'     => $ram['buffers'],
            'disk'        => $diskInfo['percent'],
            'disk_used'   => $diskInfo['used'],
            'disk_total'  => $diskInfo['total'],
            'disk_free'   => $diskInfo['free'],
            'uptime'      => $uptime,
            'hostname'    => $systemInfo['hostname'],
            'os'          => $systemInfo['os'],
            'kernel'      => $systemInfo['kernel'],
            'cpu_cores'   => $systemInfo['cpu_cores'],
            'ip'          => $systemInfo['ip'],
            'timestamp'   => now()->toIso8601String(),
        ]);
    }

    /**
     * Get Memory Information
     */
    protected function getMemoryInfo(): array
    {
        $memInfo = shell_exec("free -m");
        
        if ($memInfo) {
            preg_match('/Mem:\s+(\d+)\s+(\d+)\s+(\d+)\s+\d+\s+(\d+)/', $memInfo, $matches);
            
            return [
                'total'   => (int) ($matches[1] ?? 0),
                'used'    => (int) ($matches[2] ?? 0),
                'free'    => (int) ($matches[3] ?? 0),
                'buffers' => (int) ($matches[4] ?? 0),
            ];
        }

        return [
            'total'   => 0,
            'used'    => 0,
            'free'    => 0,
            'buffers' => 0,
        ];
    }

    /**
     * Get Disk Information
     */
    protected function getDiskInfo(): array
    {
        $diskTotal = disk_total_space("/");
        $diskFree  = disk_free_space("/");
        $diskUsed  = $diskTotal - $diskFree;
        $diskPercent = round(($diskUsed / $diskTotal) * 100, 1);

        return [
            'total'   => $this->formatBytes($diskTotal),
            'used'    => $this->formatBytes($diskUsed),
            'free'    => $this->formatBytes($diskFree),
            'percent' => $diskPercent,
        ];
    }

    /**
     * Get System Information
     */
    protected function getSystemInfo(): array
    {
        $hostname = trim(shell_exec("hostname") ?? 'Unknown');
        $os = trim(shell_exec("lsb_release -d -s 2>/dev/null") ?? php_uname('s'));
        $kernel = trim(shell_exec("uname -r") ?? php_uname('r'));
        $cpuCores = trim(shell_exec("nproc") ?? '1');
        $ip = trim(shell_exec("hostname -I | awk '{print $1}'") ?? '127.0.0.1');

        return [
            'hostname'  => $hostname,
            'os'        => $os,
            'kernel'    => $kernel,
            'cpu_cores' => (int) $cpuCores,
            'ip'        => $ip,
        ];
    }

    /**
     * Format bytes to human readable format
     */
    protected function formatBytes($bytes, $precision = 1): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
