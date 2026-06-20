@extends('layouts.app')
@section('title', $job->title)

@section('content')
{{-- Job Header --}}
<div class="flex items-start justify-between mb-6">
    <div>
        <a href="{{ route('jobs.index') }}" class="text-sm text-gray-400 hover:text-gray-600">← All Jobs</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-1">{{ $job->title }}</h1>
        <p class="text-gray-500 text-sm mt-0.5">{{ $job->company }} · {{ $job->location }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('resumes.create', $job) }}" class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm shadow-sm hover:bg-orange-600 transition-colors">
            + Upload Resume
        </a>
        <form method="POST" action="{{ route('jobs.destroy', $job) }}" onsubmit="return confirm('Delete this job and all its resumes?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="border border-gray-300 text-gray-500 px-4 py-2 rounded-lg text-sm hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-colors">
                Delete Job
            </button>
        </form>
    </div>
</div>

@if($resumes->contains('status', 'pending'))
<div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-lg px-4 py-3 mb-6 text-sm text-yellow-800">
    ⏳ Claude AI is screening the pending resumes in the background. Refresh this page to see updated rankings.
</div>
@endif

{{-- Candidates Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800">
            Candidates
            <span class="text-gray-400 font-normal text-sm ml-1">({{ $resumes->total() }} total · ranked by AI score)</span>
        </h2>
    </div>

    @forelse($resumes as $resume)
    @php $screening = $resume->screening; @endphp
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 last:border-0 hover:bg-blue-50 transition-colors">
        <div class="flex items-center gap-4">
            {{-- Score Badge --}}
            @if($screening)
            <div class="text-center w-14">
                <div class="text-xl font-bold
                    {{ $screening->score >= 80 ? 'text-green-600' : ($screening->score >= 60 ? 'text-yellow-600' : 'text-red-500') }}">
                    {{ $screening->score }}
                </div>
                <div class="text-xs text-gray-400">/100</div>
            </div>
            @else
            <div class="text-center w-14">
                <div class="text-sm text-gray-400">—</div>
            </div>
            @endif

            <div>
                <p class="font-medium text-gray-900">{{ $resume->candidate_name }}</p>
                <p class="text-sm text-gray-400">{{ $resume->candidate_email ?? 'No email' }}</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-xs font-medium px-2.5 py-1 rounded-full
                {{ $resume->status === 'screened' ? 'bg-green-500 text-white' :
                   ($resume->status === 'failed' ? 'bg-red-500 text-white' : 'bg-yellow-500 text-white') }}">
                {{ ucfirst($resume->status) }}
            </span>
            <a href="{{ route('resumes.show', $resume) }}" class="text-sm text-blue-700 hover:text-orange-500 font-medium hover:underline">View →</a>
        </div>
    </div>
    @empty
    <div class="px-6 py-16 text-center text-gray-400">
        <p class="text-lg mb-2">No resumes uploaded yet.</p>
        <a href="{{ route('resumes.create', $job) }}" class="text-blue-700 hover:underline">Upload the first resume →</a>
    </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $resumes->links() }}
</div>
@endsection
