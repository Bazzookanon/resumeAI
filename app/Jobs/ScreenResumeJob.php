<?php

namespace App\Jobs;

use App\Models\Resume;
use App\Services\ClaudeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ScreenResumeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Resume $resume,
        protected string $resumeText,
        protected string $jobDescription
    ) {}

    public function handle(ClaudeService $claudeService): void
    {
        try {
            $result = $claudeService->screenResume($this->jobDescription, $this->resumeText);

            $this->resume->screening()->create([
                'score'      => $result['score'],
                'summary'    => $result['summary'],
                'strengths'  => $result['strengths'],
                'weaknesses' => $result['weaknesses'],
                'links'      => $result['links'] ?? [],
            ]);

            $this->resume->update([
                'candidate_name'  => $result['name'] ?: $this->resume->candidate_name,
                'candidate_email' => $result['email'] ?: $this->resume->candidate_email,
                'status'          => 'screened',
            ]);
        } catch (\Exception $e) {
            Log::error("Resume screening failed for resume #{$this->resume->id}: {$e->getMessage()}");
            $this->resume->update(['status' => 'failed']);
        }
    }
}
