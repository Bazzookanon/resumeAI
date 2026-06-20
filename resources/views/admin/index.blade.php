@extends('layouts.app')
@section('title', 'Admin Panel')

@section('content')
<h1 class="text-2xl font-bold text-gray-900 mb-6">Admin Panel</h1>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <p class="text-sm text-gray-500">Total Users</p>
        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_users'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <p class="text-sm text-gray-500">Online Now</p>
        <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['online_users'] }}</p>
        <p class="text-xs text-gray-400 mt-1">Active in the last 5 minutes</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <p class="text-sm text-gray-500">Jobs in Queue</p>
        <p class="text-3xl font-bold text-blue-700 mt-1">{{ $stats['queue_pending'] }}</p>
        <p class="text-xs text-gray-400 mt-1">Resumes awaiting AI screening</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <p class="text-sm text-gray-500">Failed Queue Jobs</p>
        <p class="text-3xl font-bold {{ $stats['queue_failed'] > 0 ? 'text-red-500' : 'text-gray-900' }} mt-1">{{ $stats['queue_failed'] }}</p>
    </div>
</div>

{{-- System Health --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800">System Health</h2>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 divide-y sm:divide-y-0 sm:divide-x divide-gray-100">
        <div class="p-6 space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Database</span>
                @if($health['database'])
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-green-500 text-white">✅ Connected</span>
                @else
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-red-500 text-white">❌ Unreachable</span>
                @endif
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Claude API</span>
                @if($health['claude_api'])
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-green-500 text-white">✅ Reachable</span>
                @else
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-red-500 text-white">❌ Unreachable</span>
                @endif
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Resume Storage Used</span>
                <span class="text-sm font-medium text-gray-900">{{ $health['storage_used'] }}</span>
            </div>
        </div>
        <div class="p-6 space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Laravel Version</span>
                <span class="text-sm font-medium text-gray-900">{{ $health['laravel_version'] }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">PHP Version</span>
                <span class="text-sm font-medium text-gray-900">{{ $health['php_version'] }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Recent Logins --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800">Recent Logins</h2>
    </div>

    @forelse($recentLogins as $login)
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 last:border-0">
        <div>
            <p class="font-medium text-gray-900">{{ $login->user?->name ?? 'Deleted user' }}</p>
            <p class="text-sm text-gray-400">{{ $login->user?->email }} · {{ $login->ip_address ?? 'Unknown IP' }}</p>
        </div>
        <span class="text-sm text-gray-400">{{ $login->created_at->diffForHumans() }}</span>
    </div>
    @empty
    <div class="px-6 py-16 text-center text-gray-400">
        <p class="text-lg">No logins recorded yet.</p>
    </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $recentLogins->links() }}
</div>
@endsection
