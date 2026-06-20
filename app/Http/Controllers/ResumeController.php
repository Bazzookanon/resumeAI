<?php

namespace App\Http\Controllers;

use App\Jobs\ScreenResumeJob;
use App\Models\Job;
use App\Models\Resume;
use App\Services\PdfParserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ResumeController extends Controller
{
    public function __construct(
        protected PdfParserService $pdfParserService
    ) {}

    public function create(Job $job)
    {
        $this->authorize('view', $job);
        return view('resumes.create', compact('job'));
    }

    public function store(Request $request, Job $job)
    {
        $this->authorize('view', $job);

        $request->validate([
            'resume_files'   => 'required|array|max:20',
            'resume_files.*' => 'file|mimes:pdf|max:5120', // 5MB max per file
        ]);

        $uploaded = 0;

        foreach ($request->file('resume_files') as $file) {
            $filePath = $file->store('resumes', 'local');
            $fileName = $file->getClientOriginalName();

            // Extract text from PDF
            try {
                $rawText = $this->pdfParserService->extractText(Storage::disk('local')->path($filePath));
            } catch (\Exception $e) {
                Storage::disk('local')->delete($filePath);
                continue;
            }

            // Save resume record; Claude will fill in the real candidate name/email once screened.
            // The extracted text is never persisted — it's sent straight to Claude via the queued job.
            $resume = $job->resumes()->create([
                'candidate_name'  => Str::of(pathinfo($fileName, PATHINFO_FILENAME))
                    ->replace(['_', '-'], ' ')
                    ->title(),
                'candidate_email' => null,
                'file_path'       => $filePath,
                'file_name'       => $fileName,
                'status'          => 'pending',
            ]);

            ScreenResumeJob::dispatch($resume, $rawText, $job->description);
            $uploaded++;
        }

        if ($uploaded === 0) {
            return back()->withErrors(['resume_files' => 'No resumes could be processed. Make sure each file is a readable PDF.']);
        }

        return redirect()->route('jobs.show', $job)
            ->with('success', "{$uploaded} resume(s) uploaded and queued for AI screening.");
    }

    public function show(Resume $resume)
    {
        $this->authorize('view', $resume->job);
        $resume->load('screening', 'job');
        return view('resumes.show', compact('resume'));
    }

    public function destroy(Resume $resume)
    {
        $this->authorize('view', $resume->job);

        Storage::disk('local')->delete($resume->file_path);
        $resume->delete();

        return back()->with('success', 'Resume deleted.');
    }
}
