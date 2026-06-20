@extends('layouts.app')
@section('title', 'All Jobs')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">All Jobs</h1>
    <a href="{{ route('dashboard') }}" class="bg-orange-500 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-orange-600 transition-colors text-sm">
        + New Job
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    @forelse($jobs as $job)
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 last:border-0 hover:bg-blue-50 transition-colors">
        <div>
            <a href="{{ route('jobs.show', $job) }}" class="text-lg font-medium text-gray-900 hover:text-blue-700 transition-colors">
                {{ $job->title }}
            </a>
            <p class="text-sm text-gray-400 mt-0.5">
                {{ $job->company ?? 'No company' }} · {{ $job->location ?? 'Remote' }} · {{ $job->resumes_count }} applicant(s)
            </p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $job->status === 'open' ? 'bg-green-500 text-white' : 'bg-gray-400 text-white' }}">
                {{ ucfirst($job->status) }}
            </span>
            <a href="{{ route('jobs.show', $job) }}" class="text-sm text-blue-700 hover:text-orange-500 font-medium hover:underline">View →</a>
        </div>
    </div>
    @empty
    <div class="px-6 py-16 text-center text-gray-400">
        <p class="text-lg mb-2">No jobs created yet.</p>
        <a href="{{ route('dashboard') }}" class="text-blue-700 hover:underline">Create your first job →</a>
    </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $jobs->links() }}
</div>
@endsection
