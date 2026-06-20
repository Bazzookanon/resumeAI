@extends('layouts.app')
@section('title', 'Upload Resumes')

@section('content')
<div class="max-w-xl mx-auto">
    <a href="{{ route('jobs.show', $job) }}" class="text-sm text-gray-400 hover:text-gray-600">← Back to {{ $job->title }}</a>
    <h1 class="text-2xl font-bold text-gray-900 mt-2 mb-6">Upload Resumes</h1>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="bg-blue-50 border border-blue-100 rounded-lg px-4 py-3 mb-6 text-sm text-blue-800">
            🤖 Claude AI will read each resume, identify the candidate, and rank them against the <strong>{{ $job->title }}</strong> job description.
        </div>

        <form method="POST" action="{{ route('resumes.store', $job) }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Resume PDFs *</label>
                <input type="file" name="resume_files[]" accept=".pdf" multiple
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p class="text-xs text-gray-400 mt-1">PDF only · Max 5MB each · Up to 20 files at once</p>
                @error('resume_files') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                @error('resume_files.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full bg-orange-500 text-white py-2.5 rounded-lg shadow-sm hover:bg-orange-600 transition-colors text-sm font-medium">
                Upload & Screen with Claude AI
            </button>
        </form>
    </div>
</div>
@endsection
