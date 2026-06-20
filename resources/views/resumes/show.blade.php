@extends('layouts.app')
@section('title', $resume->candidate_name)

@section('content')
<a href="{{ route('jobs.show', $resume->job) }}" class="text-sm text-gray-400 hover:text-gray-600">← Back to {{ $resume->job->title }}</a>

<div class="mt-4 grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left: Candidate Info --}}
    <div class="lg:col-span-1 space-y-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="font-semibold text-gray-800 mb-4">Candidate</h2>
            <p class="text-xl font-bold text-gray-900">{{ $resume->candidate_name }}</p>
            <p class="text-sm text-gray-400 mt-1">{{ $resume->candidate_email ?? 'No email provided' }}</p>
            <hr class="my-4">
            <p class="text-sm text-gray-500">Applied for</p>
            <p class="font-medium text-blue-700">{{ $resume->job->title }}</p>
            <p class="text-xs text-gray-400 mt-0.5">{{ $resume->job->company }}</p>
            <hr class="my-4">
            <p class="text-xs text-gray-400">Uploaded {{ $resume->created_at->diffForHumans() }}</p>
        </div>

        {{-- Delete --}}
        <form method="POST" action="{{ route('resumes.destroy', $resume) }}"
            onsubmit="return confirm('Delete this resume?')">
            @csrf @method('DELETE')
            <button type="submit" class="w-full text-sm text-red-500 border border-red-200 rounded-lg py-2 hover:bg-red-50 transition-colors">
                Delete Resume
            </button>
        </form>
    </div>

    {{-- Right: AI Screening Results --}}
    <div class="lg:col-span-2 space-y-4">
        @if($resume->screening)
        @php $s = $resume->screening; @endphp

        {{-- Score --}}
        @php
            $scoreColor = match(true) {
                $s->score >= 80 => 'text-green-600',
                $s->score >= 60 => 'text-yellow-600',
                default => 'text-red-500',
            };
        @endphp
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center gap-6">
            <div class="text-center">
                <div class="text-5xl font-extrabold {{ $scoreColor }}">
                    {{ $s->score }}
                </div>
                <div class="text-sm text-gray-400 mt-1">out of 100</div>
            </div>
            <div>
                <p class="text-xs font-semibold text-blue-700 uppercase tracking-wide mb-1">AI Summary</p>
                <p class="text-gray-700 text-sm leading-relaxed">{{ $s->summary }}</p>
            </div>
        </div>

        {{-- Strengths & Weaknesses --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-green-50 rounded-xl border border-green-100 p-5">
                <h3 class="text-sm font-semibold text-green-700 mb-3">✅ Strengths</h3>
                <ul class="space-y-2">
                    @foreach($s->strengths as $strength)
                    <li class="text-sm text-green-800 flex gap-2">
                        <span class="mt-0.5 shrink-0">•</span>{{ $strength }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="bg-red-50 rounded-xl border border-red-100 p-5">
                <h3 class="text-sm font-semibold text-red-600 mb-3">⚠️ Weaknesses</h3>
                <ul class="space-y-2">
                    @foreach($s->weaknesses as $weakness)
                    <li class="text-sm text-red-700 flex gap-2">
                        <span class="mt-0.5 shrink-0">•</span>{{ $weakness }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Links --}}
        @if(!empty($s->links))
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">🔗 Links</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($s->links as $link)
                    @php
                        $label = $link['label'] ?? 'Link';
                        [$icon, $pillClass] = match(true) {
                            str_contains(strtolower($label), 'github')   => ['🐙', 'bg-gray-800 text-white hover:bg-gray-900'],
                            str_contains(strtolower($label), 'linkedin') => ['💼', 'bg-sky-600 text-white hover:bg-sky-700'],
                            str_contains(strtolower($label), 'portfolio') => ['🌐', 'bg-teal-600 text-white hover:bg-teal-700'],
                            default => ['🔗', 'bg-blue-700 text-white hover:bg-blue-800'],
                        };
                    @endphp
                <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer"
                    class="text-sm px-3 py-1.5 rounded-lg shadow-sm transition-colors {{ $pillClass }}">
                    {{ $icon }} {{ $label }} ↗
                </a>
                @endforeach
            </div>
        </div>
        @endif

        @elseif($resume->status === 'failed')
        <div class="bg-red-50 border-l-4 border-red-400 rounded-xl p-6 text-red-700 text-sm">
            ❌ AI screening failed for this resume. Please try re-uploading.
        </div>
        @else
        <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-xl p-6 text-yellow-800 text-sm">
            ⏳ This resume is pending AI screening.
        </div>
        @endif
    </div>
</div>
@endsection
