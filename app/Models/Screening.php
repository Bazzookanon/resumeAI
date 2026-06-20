<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Screening extends Model
{
    use HasFactory;

    protected $fillable = [
        'resume_id',
        'score',
        'summary',
        'strengths',
        'weaknesses',
        'links',
    ];

    protected $casts = [
        'strengths' => 'array',
        'weaknesses' => 'array',
        'links' => 'array',
    ];

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class);
    }

    public function getScoreBadgeColorAttribute(): string
    {
        return match(true) {
            $this->score >= 80 => 'green',
            $this->score >= 60 => 'yellow',
            default            => 'red',
        };
    }
}
