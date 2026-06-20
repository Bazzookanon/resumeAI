<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class ClaudeService
{
    protected string $apiKey;
    protected string $model = 'claude-sonnet-4-6';
    protected string $apiVersion = '2023-06-01';

    public function __construct()
    {
        $this->apiKey = config('services.claude.api_key');
    }

    public function screenResume(string $jobDescription, string $resumeText): array
    {
        $prompt = <<<PROMPT
You are an expert HR recruiter and talent evaluator. Your task is to read a candidate's resume, identify the candidate, and analyze their fit against a job description.

JOB DESCRIPTION:
{$jobDescription}

CANDIDATE RESUME:
{$resumeText}

Carefully evaluate the candidate based on:
- Relevant skills and experience
- Educational background
- Achievements and accomplishments
- Overall fit for the role

Also look for any URLs in the resume pointing to the candidate's GitHub profile/repositories, portfolio site, live projects/demos, or LinkedIn.

Respond ONLY with a valid JSON object. No markdown, no code blocks, no extra text. Use this exact structure:
{
  "name": "<candidate's full name as found in the resume, or null if not found>",
  "email": "<candidate's email as found in the resume, or null if not found>",
  "score": <integer 0-100>,
  "summary": "<2-3 sentence overall assessment>",
  "strengths": ["<strength 1>", "<strength 2>", "<strength 3>"],
  "weaknesses": ["<weakness 1>", "<weakness 2>"],
  "links": [{"label": "<e.g. GitHub, Portfolio, Project Name, LinkedIn>", "url": "<the URL>"}]
}
If no links are found, return an empty array for "links".
PROMPT;

        $response = Http::timeout(30)
            ->withHeaders([
                'x-api-key'         => $this->apiKey,
                'anthropic-version' => $this->apiVersion,
                'content-type'      => 'application/json',
            ])
            ->post('https://api.anthropic.com/v1/messages', [
                'model'      => $this->model,
                'max_tokens' => 1024,
                'messages'   => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

        if ($response->failed()) {
            throw new Exception('Claude API request failed: ' . $response->body());
        }

        $text = $response->json('content.0.text');

        $result = json_decode($text, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Failed to parse Claude API response as JSON.');
        }

        return $result;
    }

    /**
     * Minimal live ping to confirm the API key/connection works, for the admin health panel.
     */
    public function checkHealth(): bool
    {
        if (empty($this->apiKey)) {
            return false;
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'x-api-key'         => $this->apiKey,
                    'anthropic-version' => $this->apiVersion,
                    'content-type'      => 'application/json',
                ])
                ->post('https://api.anthropic.com/v1/messages', [
                    'model'      => $this->model,
                    'max_tokens' => 1,
                    'messages'   => [
                        ['role' => 'user', 'content' => 'ping'],
                    ],
                ]);

            return $response->successful();
        } catch (Exception $e) {
            return false;
        }
    }
}
