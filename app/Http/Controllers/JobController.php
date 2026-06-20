<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Auth::user()->jobs()->withCount('resumes')->latest()->paginate(10);
        return view('jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('jobs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'company'     => 'nullable|string|max:255',
            'location'    => 'nullable|string|max:255',
        ]);

        $job = Auth::user()->jobs()->create($validated);

        return redirect()->route('resumes.create', $job)
            ->with('success', 'Job created! Now upload resumes to screen.');
    }

    public function show(Job $job)
    {
        $this->authorize('view', $job);

        $resumes = $job->resumes()
            ->with('screening')
            ->orderByDesc(function ($query) {
                $query->select('score')
                    ->from('screenings')
                    ->whereColumn('resume_id', 'resumes.id')
                    ->limit(1);
            })
            ->paginate(10);

        return view('jobs.show', compact('job', 'resumes'));
    }

    public function destroy(Job $job)
    {
        $this->authorize('delete', $job);
        $job->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Job deleted successfully!');
    }
}
