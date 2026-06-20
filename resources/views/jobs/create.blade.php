@extends('layouts.app')
@section('title', 'Create Job Posting')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Create Job Posting</h1>

    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
        <form method="POST" action="{{ route('jobs.store') }}">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">Job Title *</label>
                <input type="text" name="title" value="{{ old('title') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="e.g. Senior Laravel Developer">
                @error('title') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                    <input type="text" name="company" value="{{ old('company') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Your company name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <input type="text" name="location" value="{{ old('location') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="e.g. Remote, Manila">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Job Description *</label>
                <p class="text-xs text-gray-400 mb-2">Be detailed — Claude AI uses this to evaluate resumes.</p>
                <textarea name="description" rows="10"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Describe the role, required skills, responsibilities, and qualifications...">{{ old('description') }}</textarea>
                @error('description') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-orange-500 text-white px-5 py-2 rounded-lg shadow-sm hover:bg-orange-600 transition-colors text-sm font-medium">
                    Create Job & Upload Resumes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
