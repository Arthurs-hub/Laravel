<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Facility;
use App\Models\Hotel;
use App\Models\Review;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

class SystemStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        $systemInfo = $this->getSystemInfo();
        $databaseInfo = $this->getDatabaseInfo();
        $performanceInfo = $this->getPerformanceInfo();
        $storageInfo = $this->getStorageInfo();

        return view('admin.system-status', compact(
            'systemInfo',
            'databaseInfo', 
            'performanceInfo',
            'storageInfo'
        ));
    }

    private function getSystemInfo()
    {
        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'operating_system' => PHP_OS,
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'environment' => config('app.env'),
            'debug_mode' => config('app.debug'),
            'uptime' => $this->getServerUptime(),
        ];
    }

    private function getDatabaseInfo()
    {
        try {
            $connection = DB::connection();
            $pdo = $connection->getPdo();

            $databaseName = $connection->getDatabaseName();
            $sizeQuery = "SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb 
                         FROM information_schema.tables 
                         WHERE table_schema = ?";
            $databaseSize = DB::selectOne($sizeQuery, [$databaseName])->size_mb ?? 0;

            return [
                'status' => 'connected',
                'driver' => $connection->getDriverName(),
                'database_name' => $databaseName,
                'database_size_mb' => $databaseSize,
                'total_users' => User::count(),
                'total_hotels' => Hotel::count(),
                'total_rooms' => Room::count(),
                'total_bookings' => Booking::count(),
                'total_reviews' => Review::count(),
                'total_facilities' => Facility::count(),
                'pending_reviews' => Review::where('is_approved', false)->count(),
                'recent_bookings' => Booking::where('created_at', '>=', now()->subDays(7))->count(),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }
    }

    private function getPerformanceInfo()
    {
        $memoryUsage = memory_get_usage(true);
        $memoryPeak = memory_get_peak_usage(true);
        $memoryLimit = $this->convertToBytes(ini_get('memory_limit'));

        $cacheStatus = 'error';
        try {
            Cache::put('system_status_test', 'test', 60);
            if (Cache::get('system_status_test') === 'test') {
                $cacheStatus = 'working';
                Cache::forget('system_status_test');
            }
        } catch (\Exception $e) {
            $cacheStatus = 'error: ' . $e->getMessage();
        }

        $queueStatus = 'unknown';
        try {
            $queueSize = Queue::size();
            $queueStatus = $queueSize < 100 ? 'healthy' : 'overloaded (' . $queueSize . ' jobs)';
        } catch (\Exception $e) {
            $queueStatus = 'error: ' . $e->getMessage();
        }

        return [
            'memory_usage_mb' => round($memoryUsage / 1024 / 1024, 2),
            'memory_peak_mb' => round($memoryPeak / 1024 / 1024, 2),
            'memory_limit_mb' => round($memoryLimit / 1024 / 1024, 2),
            'memory_usage_percent' => round(($memoryUsage / $memoryLimit) * 100, 2),
            'cache_status' => $cacheStatus,
            'queue_status' => $queueStatus,
            'opcache_enabled' => function_exists('opcache_get_status') && opcache_get_status() !== false,
        ];
    }

    private function getStorageInfo()
    {
        try {
            $storagePath = storage_path();
            $totalSpace = disk_total_space($storagePath);
            $freeSpace = disk_free_space($storagePath);
            $usedSpace = $totalSpace - $freeSpace;

            $storageStatus = 'error';
            try {
                Storage::put('system_status_test.txt', 'test');
                if (Storage::exists('system_status_test.txt')) {
                    $storageStatus = 'writable';
                    Storage::delete('system_status_test.txt');
                }
            } catch (\Exception $e) {
                $storageStatus = 'error: ' . $e->getMessage();
            }

            return [
                'total_space_gb' => round($totalSpace / 1024 / 1024 / 1024, 2),
                'free_space_gb' => round($freeSpace / 1024 / 1024 / 1024, 2),
                'used_space_gb' => round($usedSpace / 1024 / 1024 / 1024, 2),
                'usage_percent' => round(($usedSpace / $totalSpace) * 100, 2),
                'storage_status' => $storageStatus,
                'storage_path' => $storagePath,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }
    }

    private function getServerUptime()
    {
        if (PHP_OS_FAMILY === 'Linux' || PHP_OS_FAMILY === 'Darwin') {
            $uptime = shell_exec('uptime -p 2>/dev/null');
            return $uptime ? trim($uptime) : 'Unknown';
        }
        return 'Unknown';
    }

    private function convertToBytes($value)
    {
        $value = trim($value);
        $last = strtolower($value[strlen($value) - 1]);
        $value = (int) $value;

        switch ($last) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }

        return $value;
    }
}
