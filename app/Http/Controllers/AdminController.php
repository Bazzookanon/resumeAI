<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use App\Models\User;
use App\Services\ClaudeService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index(ClaudeService $claudeService)
    {
        $stats = [
            'total_users'  => User::count(),
            'online_users' => User::where('last_seen_at', '>=', now()->subMinutes(5))->count(),
            'queue_pending' => DB::table('jobs')->count(),
            'queue_failed'  => DB::table('failed_jobs')->count(),
        ];

        $health = [
            'database'    => $this->checkDatabase(),
            'claude_api'  => Cache::remember('admin.health.claude_api', 60, fn () => $claudeService->checkHealth()),
            'storage_used' => $this->resumesStorageSize(),
            'php_version'     => PHP_VERSION,
            'laravel_version' => app()->version(),
        ];

        $recentLogins = LoginLog::with('user')->latest()->paginate(15);

        return view('admin.index', compact('stats', 'health', 'recentLogins'));
    }

    private function checkDatabase(): bool
    {
        try {
            DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function resumesStorageSize(): string
    {
        $bytes = 0;

        foreach (Storage::disk('local')->allFiles('resumes') as $file) {
            $bytes += Storage::disk('local')->size($file);
        }

        return number_format($bytes / 1048576, 2) . ' MB';
    }
}
